<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
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

    #[Test]
    public function it_does_nothing_if_the_response_is_not_a_404()
    {
        $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertEquals(0, Error::query()->count());
    }

    #[Test]
    public function it_does_nothing_if_redirect_is_not_enabled()
    {
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

    #[Test]
    public function it_creates_an_error_when_the_response_is_404_and_saves_metadata()
    {
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
        tap(Error::findByUrl('/abc'), function (Error $error) {
            $this->assertEquals('/abc', $error->url);
            $this->assertEquals(1, count($error->hits));
            $this->assertEquals(now()->timestamp, $error->lastSeenAt);
            $this->assertEquals('Symfony', $error->hits[0]['data']['userAgent']);
            $this->assertEquals('127.0.0.1', $error->hits[0]['data']['ip']);
            $this->assertEquals('some-referer', $error->hits[0]['data']['referer']);
        });
    }

    #[Test]
    #[DataProvider('provideRedirects')]
    public function it_redirects_and_sets_handled_if_a_redirect_is_found(string $source, string $destination, string $requestUrl, string $result)
    {
        Redirect::make()
            ->source($source)
            ->destination($destination)
            ->save();

        $response = $this->middleware->handle(Request::create($requestUrl), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        tap(Error::findByUrl($source), function (Error $error) use ($destination) {
            $this->assertEquals(true, $error->handled);
            $this->assertEquals($destination, $error->handledDestination);
        });

        $this->assertTrue($response->isRedirect(url($result)));
    }

    #[Test]
    #[DataProvider('provideRedirects')]
    public function it_redirects_and_sets_handled_if_a_redirect_is_found_with_eloquent(string $source, string $destination, string $requestUrl, string $result)
    {
        config()->set('statamic.redirect.redirect_connection', 'redirect-sqlite');

        DB::setDefaultConnection('redirect-sqlite');
        Schema::dropIfExists('redirects');
        require_once(__DIR__ . '/../../../database/migrations/create_redirect_redirects_table.php.stub');
        (new \CreateRedirectRedirectsTable())->up();
        require_once(__DIR__ . '/../../../database/migrations/add_description_to_redirect_redirects_table.php.stub');
        (new \AddDescriptionToRedirectRedirectsTable())->up();
        require_once(__DIR__ . '/../../../database/migrations/increase_redirect_redirects_table_url_length.php.stub');
        (new \IncreaseRedirectRedirectsTableUrlLength())->up();

        app()->singleton(RedirectRepository::class, function () {
            return new \Rias\StatamicRedirect\Eloquent\Redirects\RedirectRepository(app('stache'));
        });
        Redirect::clearResolvedInstances();

        Redirect::make()
            ->source($source)
            ->destination($destination)
            ->save();

        $response = $this->middleware->handle(Request::create($requestUrl), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        tap(Error::findByUrl($source), function (Error $error) use ($destination) {
            $this->assertEquals(true, $error->handled);
            $this->assertEquals($destination, $error->handledDestination);
        });

        $this->assertTrue($response->isRedirect(url($result)));
    }

    public static function provideRedirects(): array
    {
        return [
            ['/abc', '/def', '/abc', '/def'],
            ['/abc', '/def', 'abc', '/def'],
            ['/abc', '/def', '/abc/', '/def'],
            ['/abc', '/def', 'abc/', '/def'],
        ];
    }

    #[Test]
    public function it_handles_401_redirects()
    {
        //$this->withExceptionHandling();

        Redirect::make()
            ->source('/abc')
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
        $this->assertEquals(true, Error::findByUrl('/abc')->handled);
    }

    #[Test]
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

    #[Test]
    public function it_can_preserve_query_strings()
    {
        config()->set('statamic.redirect.preserve_query_strings', true);

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

        $this->assertTrue($response->isRedirect(url('/nl?lang=nl')));

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'fr']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/fr?lang=fr')));
    }

    #[Test]
    public function it_merges_query_strings()
    {
        config()->set('statamic.redirect.preserve_query_strings', true);

        Redirect::make()
            ->source('/abc?another=value')
            ->destination('/fr?lang=fr')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/fr?another=value&lang=fr')));
    }

    #[Test]
    public function it_merges_query_strings_on_urls_without_path()
    {
        config()->set('statamic.redirect.preserve_query_strings', true);

        Redirect::make()
            ->source('/abc?another=value')
            ->destination('?lang=fr')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('?another=value&lang=fr')));
    }

    #[Test]
    public function it_merges_query_strings_on_urls_with_fragment()
    {
        config()->set('statamic.redirect.preserve_query_strings', true);

        Redirect::make()
            ->source('/abc?another=value')
            ->destination('/abc?lang=fr#some-fragment?with=fragment_param')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/abc?another=value&lang=fr#some-fragment?with=fragment_param')));
    }

    #[Test]
    public function it_merges_query_strings_on_urls_without_path_with_fragment()
    {
        config()->set('statamic.redirect.preserve_query_strings', true);

        Redirect::make()
            ->source('/abc?another=value')
            ->destination('?lang=fr#some-fragment?with=fragment_param')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('?another=value&lang=fr#some-fragment?with=fragment_param')));
    }


    #[Test]
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

    #[Test]
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

    #[Test]
    public function it_handles_trailing_slashes()
    {
        Redirect::make()
            ->source('/foo')
            ->destination('/bar')
            ->matchType(MatchTypeEnum::EXACT)
            ->save();

        $response = $this->middleware->handle(Request::create('/foo/'), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/bar')));
    }

    #[Test]
    public function it_handles_if_the_source_has_a_trailing_slash()
    {
        Redirect::make()
            ->source('/foo/')
            ->destination('/bar')
            ->matchType(MatchTypeEnum::EXACT)
            ->save();

        $response = $this->middleware->handle(Request::create('/foo'), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isRedirect(url('/bar')));
    }

    #[Test]
    public function it_cleans_if_config_is_set_to_clean()
    {
        config()->set('statamic.redirect.clean_errors', true);
        config()->set('statamic.redirect.clean_errors_on_save', true);
        config()->set('statamic.redirect.keep_unique_errors', 1);

        Error::create(['url' => 'url1'])->addHit(now()->timestamp);
        Error::create(['url' => 'url2'])->addHit(now()->timestamp);
        Error::create(['url' => 'url3'])->addHit(now()->timestamp);

        $request = Request::create('/abc');

        $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::count());
    }

    #[Test]
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

    #[Test]
    public function it_doesnt_log_hits_if_log_hits_is_false()
    {
        config()->set('statamic.redirect.log_errors', true);
        config()->set('statamic.redirect.log_hits', false);

        $request = Request::create('/abc');

        $response = $this->middleware->handle($request, function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals(0, count(Error::findByUrl('/abc')->hits ?? []));
        $this->assertEquals(1, Error::findByUrl('/abc')->hitsCount); // Still add count
        $this->assertEquals(404, $response->status());
    }

    /** @test * */
    public function it_will_not_find_redirects_from_different_sites()
    {
        Redirect::make()
            ->site('some-different-site')
            ->source('/abc')
            ->destination('/def')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertTrue($response->isNotFound());
        $this->assertEquals(1, Error::query()->count());
    }
}
