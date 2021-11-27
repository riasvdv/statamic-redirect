<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Error;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandleNotFoundTest extends TestCase
{
    /** @var HandleNotFound */
    private $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = app(HandleNotFound::class);
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_does_nothing_if_the_response_is_not_a_404($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertEquals(0, Error::query()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_does_nothing_if_redirect_is_not_enabled($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        config()->set('statamic.redirect.enable', false);

        $request = Request::create('/abc');
        $request->headers->add([
            'referer' => 'some-referer',
        ]);

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(0, Error::query()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_creates_an_error_when_the_response_is_404_and_saves_metadata($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        Carbon::setTestNow(now());

        $request = Request::create('/abc');
        $request->headers->add([
            'referer' => 'some-referer',
        ]);

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals(404, $response->status());
        tap(Error::findByUrl('/abc'), function (\Rias\StatamicRedirect\Data\Error $error) {
            $this->assertEquals('/abc', $error->url());
            $this->assertEquals(1, count($error->hits()));
            $this->assertEquals(now()->timestamp, $error->lastSeenAt());
            $this->assertEquals('Symfony', $error->hits()[0]['data']['userAgent']);
            $this->assertEquals('127.0.0.1', $error->hits()[0]['data']['ip']);
            $this->assertEquals('some-referer', $error->hits()[0]['data']['referer']);
        });
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_redirects_and_sets_handled_if_a_redirect_is_found($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        Redirect::make()
            ->source('/abc')
            ->destination('/def')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        tap(Error::findByUrl('/abc'), function (\Rias\StatamicRedirect\Data\Error $error) {
            $this->assertEquals(true, $error->handled());
            $this->assertEquals('/def', $error->handledDestination());
        });

        $this->assertTrue($response->isRedirect(url('/def')));
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_handles_401_redirects($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        $this->withExceptionHandling();

        Redirect::make()
            ->source('/abc')
            ->destination('/def')
            ->type(410)
            ->save();

        try {
            $this->middleware->handle(Request::create('/abc'), function () {
                return (new Response('', 404));
            });
        } catch (HttpException $e) {
            $this->assertEquals(410, $e->getStatusCode());
        }

        $this->assertEquals(1, Error::query()->count());
        $this->assertNotNull(Error::findByUrl('/abc'));
        $this->assertEquals(true, Error::findByUrl('/abc')->handled());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_handles_query_parameters($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        Redirect::make()
            ->source('/abc?lang=nl')
            ->destination('/nl')
            ->save();

        Redirect::make()
            ->source('/abc?lang=fr')
            ->destination('/fr')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'nl']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/nl')));

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'fr']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/fr')));
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_can_redirect_to_external_urls($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        Redirect::make()
            ->source('/abc/(.*)')
            ->destination('https://google.com?s=$1')
            ->matchType(MatchTypeEnum::REGEX)
            ->save();

        $response = $this->middleware->handle(Request::create('/abc/a'), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect('https://google.com?s=a'));
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_can_redirect_the_homepage($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        Redirect::make()
            ->source('/')
            ->destination('/blog')
            ->matchType(MatchTypeEnum::EXACT)
            ->save();

        $response = $this->middleware->handle(Request::create('/'), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/blog')));
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_cleans_if_config_is_set_to_clean($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        config()->set('statamic.redirect.clean_errors', true);
        config()->set('statamic.redirect.clean_errors_on_save', true);
        config()->set('statamic.redirect.keep_unique_errors', 1);

        Error::make()->url('url1')->addHit(now()->timestamp)->save();
        Error::make()->url('url2')->addHit(now()->timestamp)->save();
        Error::make()->url('url3')->addHit(now()->timestamp)->save();

        $request = Request::create('/abc');

        $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::all()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_doesnt_log_errors_if_log_errors_is_false($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        config()->set('statamic.redirect.log_errors');

        $request = Request::create('/abc');
        $request->headers->add([
            'referer' => 'some-referer',
        ]);

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(0, Error::query()->count());
        $this->assertEquals(404, $response->status());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_doesnt_log_hits_if_log_hits_is_false($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);
        config()->set('statamic.redirect.log_errors', true);
        config()->set('statamic.redirect.log_hits', false);

        $request = Request::create('/abc');

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals(0, count(Error::findByUrl('/abc')->hits() ?? []));
        $this->assertEquals(1, Error::findByUrl('/abc')->hitsCount()); // Still add count
        $this->assertEquals(404, $response->status());
    }
}
