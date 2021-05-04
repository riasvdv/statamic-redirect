<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    /** @test * */
    public function it_does_nothing_if_the_response_is_not_a_404()
    {
        $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertEquals(0, Error::query()->count());
    }

    /** @test * */
    public function it_creates_an_error_when_the_response_is_404_and_saves_metadata()
    {
        $request = Request::create('/abc');
        $request->headers->add([
            'referer' => 'some-referer',
        ]);

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals(404, $response->status());
        tap(Error::query()->first(), function (\Rias\StatamicRedirect\Data\Error $error) {
            $this->assertEquals('/abc', $error->url());
            $this->assertEquals(1, count($error->hits()));
            $this->assertEquals('Symfony', $error->hits()[0]['data']['userAgent']);
            $this->assertEquals('127.0.0.1', $error->hits()[0]['data']['ip']);
            $this->assertEquals('some-referer', $error->hits()[0]['data']['referer']);
        });
    }

    /** @test * */
    public function it_redirects_and_sets_handled_if_a_redirect_is_found()
    {
        Redirect::make()
            ->source('/abc')
            ->destination('/def')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals('/abc', Error::query()->first()->url());
        $this->assertEquals(true, Error::query()->first()->handled());
        $this->assertEquals('/def', Error::query()->first()->handledDestination());

        $this->assertTrue($response->isRedirect(url('/def')));
    }

    /** @test * */
    public function it_handles_401_redirects()
    {
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
        $this->assertEquals('/abc', Error::query()->first()->url());
        $this->assertEquals(true, Error::query()->first()->handled());
    }

    /** @test * */
    public function it_handles_query_parameters()
    {
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

    /** @test * */
    public function it_can_redirect_to_external_urls()
    {
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

    /** @test * */
    public function it_can_redirect_the_homepage()
    {
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

    /** @test * */
    public function it_cleans_if_config_is_set_to_clean()
    {
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

    /** @test * */
    public function it_doesnt_log_errors_if_log_errors_is_false()
    {
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
}
