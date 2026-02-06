<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Exceptions\GoneHttpException;

beforeEach(function () {
    $this->middleware = app(HandleNotFound::class);
});

it('does nothing if the response is not a 404', function () {
    $this->middleware->handle(Request::create('/abc'), function () {
        return new Response('', 200);
    });

    expect(Error::query()->count())->toEqual(0);
});

it('does nothing if redirect is not enabled', function () {
    config()->set('statamic.redirect.enable', false);

    $request = Request::create('/abc');
    $request->headers->add([
        'referer' => 'some-referer',
    ]);

    $response = $this->middleware->handle($request, function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(0);
});

it('creates an error when the response is 404 and saves metadata', function () {
    Carbon::setTestNow(now());

    $request = Request::create('/abc');
    $request->headers->add([
        'referer' => 'some-referer',
    ]);

    $response = $this->middleware->handle($request, function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(1);
    expect($response->status())->toEqual(404);
    tap(Error::findByUrl('/abc'), function (Error $error) {
        expect($error->url)->toEqual('/abc');
        expect(count($error->hits))->toEqual(1);
        expect($error->lastSeenAt)->toEqual(now()->timestamp);
        expect($error->hits[0]['data']['userAgent'])->toEqual('Symfony');
        expect($error->hits[0]['data']['ip'])->toEqual('127.0.0.1');
        expect($error->hits[0]['data']['referer'])->toEqual('some-referer');
    });
});

it('redirects and sets handled if a redirect is found', function (string $source, string $destination, string $requestUrl, string $result) {
    Redirect::make()
        ->source($source)
        ->destination($destination)
        ->save();

    $response = $this->middleware->handle(Request::create($requestUrl), function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(1);
    tap(Error::findByUrl($source), function (Error $error) use ($destination) {
        expect($error->handled)->toEqual(true);
        expect($error->handledDestination)->toEqual($destination);
    });

    expect($response->isRedirect(url($result)))->toBeTrue();
})->with('provideRedirects');

it('redirects and sets handled if a redirect is found with eloquent', function (string $source, string $destination, string $requestUrl, string $result) {
    config()->set('statamic.redirect.redirect_connection', 'redirect-sqlite');

    DB::setDefaultConnection('redirect-sqlite');
    Schema::dropIfExists('redirects');
    require_once __DIR__.'/../../../database/migrations/create_redirect_redirects_table.php.stub';
    (new \CreateRedirectRedirectsTable)->up();
    require_once __DIR__.'/../../../database/migrations/add_description_to_redirect_redirects_table.php.stub';
    (new \AddDescriptionToRedirectRedirectsTable)->up();
    require_once __DIR__.'/../../../database/migrations/increase_redirect_redirects_table_url_length.php.stub';
    (new \IncreaseRedirectRedirectsTableUrlLength)->up();
    include_once __DIR__.'/../../../database/migrations/version_4_upgrade.php.stub';
    (new \Version4UpgradeMigration)->up();

    app()->singleton(RedirectRepository::class, function () {
        return new \Rias\StatamicRedirect\Eloquent\Redirects\RedirectRepository(app('stache'));
    });
    Redirect::clearResolvedInstances();

    Redirect::make()
        ->source($source)
        ->destination($destination)
        ->save();

    $response = $this->middleware->handle(Request::create($requestUrl), function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(1);
    tap(Error::findByUrl($source), function (Error $error) use ($destination) {
        expect($error->handled)->toEqual(true);
        expect($error->handledDestination)->toEqual($destination);
    });

    expect($response->isRedirect(url($result)))->toBeTrue();
})->with('provideRedirects');

dataset('provideRedirects', function () {
    return [
        ['/abc', '/def', '/abc', '/def'],
        ['/abc', '/def', 'abc', '/def'],
        ['/abc', '/def', '/abc/', '/def'],
        ['/abc', '/def', 'abc/', '/def'],
    ];
});

it('handles 410 redirects', function () {
    Redirect::make()
        ->source('/abc')
        ->type(410)
        ->save();

    try {
        $this->middleware->handle(Request::create('/abc'), function () {
            return new Response('', 404);
        });
    } catch (GoneHttpException $e) {
        expect($e->getStatusCode())->toEqual(410);
    }

    expect(Error::query()->count())->toEqual(1);
    expect(Error::findByUrl('/abc'))->not->toBeNull();
    expect(Error::findByUrl('/abc')->handled)->toEqual(true);
});

it('handles query parameters', function () {
    Redirect::make()
        ->source('/abc?lang=nl')
        ->destination('/nl')
        ->save();

    Redirect::make()
        ->source('/abc?lang=fr')
        ->destination('/fr')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'nl']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/nl')))->toBeTrue();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'fr']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/fr')))->toBeTrue();
});

it('can preserve query strings', function () {
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
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/nl?lang=nl')))->toBeTrue();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['lang' => 'fr']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/fr?lang=fr')))->toBeTrue();
});

it('merges query strings', function () {
    config()->set('statamic.redirect.preserve_query_strings', true);

    Redirect::make()
        ->source('/abc?another=value')
        ->destination('/fr?lang=fr')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/fr?another=value&lang=fr')))->toBeTrue();
});

it('merges query strings on urls without path', function () {
    config()->set('statamic.redirect.preserve_query_strings', true);

    Redirect::make()
        ->source('/abc?another=value')
        ->destination('?lang=fr')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('?another=value&lang=fr')))->toBeTrue();
});

it('merges query strings on urls with fragment', function () {
    config()->set('statamic.redirect.preserve_query_strings', true);

    Redirect::make()
        ->source('/abc?another=value')
        ->destination('/abc?lang=fr#some-fragment?with=fragment_param')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/abc?another=value&lang=fr#some-fragment?with=fragment_param')))->toBeTrue();
});

it('merges query strings on urls without path with fragment', function () {
    config()->set('statamic.redirect.preserve_query_strings', true);

    Redirect::make()
        ->source('/abc?another=value')
        ->destination('?lang=fr#some-fragment?with=fragment_param')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc', 'GET', ['another' => 'value']), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('?another=value&lang=fr#some-fragment?with=fragment_param')))->toBeTrue();
});

it('can redirect to external urls', function () {
    Redirect::make()
        ->source('/abc/(.*)')
        ->destination('https://google.com?s=$1')
        ->matchType(MatchTypeEnum::REGEX)
        ->save();

    $response = $this->middleware->handle(Request::create('/abc/a'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect('https://google.com?s=a'))->toBeTrue();
});

it('can redirect correctly with regex', function () {
    Redirect::make()
        ->source('/notfound*')
        ->destination('/')
        ->matchType(MatchTypeEnum::REGEX)
        ->save();

    $response = $this->middleware->handle(Request::create('/notfound2'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/')))->toBeTrue();
});

it('can redirect correctly with regex placeholder', function () {
    Redirect::make()
        ->source('/notfound(.*)')
        ->destination('/$1')
        ->matchType(MatchTypeEnum::REGEX)
        ->save();

    $response = $this->middleware->handle(Request::create('/notfound2'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/2')))->toBeTrue();
});

it('can redirect the homepage', function () {
    Redirect::make()
        ->source('/')
        ->destination('/blog')
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $response = $this->middleware->handle(Request::create('/'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/blog')))->toBeTrue();
});

it('handles trailing slashes', function () {
    Redirect::make()
        ->source('/foo')
        ->destination('/bar')
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $response = $this->middleware->handle(Request::create('/foo/'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/bar')))->toBeTrue();
});

it('handles if the source has a trailing slash', function () {
    Redirect::make()
        ->source('/foo/')
        ->destination('/bar')
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $response = $this->middleware->handle(Request::create('/foo'), function () {
        return new Response('', 404);
    });

    expect($response->isRedirect(url('/bar')))->toBeTrue();
});

it('cleans if config is set to clean', function () {
    config()->set('statamic.redirect.clean_errors', true);
    config()->set('statamic.redirect.clean_errors_on_save', true);
    config()->set('statamic.redirect.keep_unique_errors', 1);

    Error::create(['url' => 'url1'])->addHit(now()->timestamp);
    Error::create(['url' => 'url2'])->addHit(now()->timestamp);
    Error::create(['url' => 'url3'])->addHit(now()->timestamp);

    $request = Request::create('/abc');

    $this->middleware->handle($request, function () {
        return new Response('', 404);
    });

    expect(Error::count())->toEqual(1);
});

it('doesnt log errors if log errors is false', function () {
    config()->set('statamic.redirect.log_errors');

    $request = Request::create('/abc');
    $request->headers->add([
        'referer' => 'some-referer',
    ]);

    $response = $this->middleware->handle($request, function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(0);
    expect($response->status())->toEqual(404);
});

it('doesnt log hits if log hits is false', function () {
    config()->set('statamic.redirect.log_errors', true);
    config()->set('statamic.redirect.log_hits', false);

    $request = Request::create('/abc');

    $response = $this->middleware->handle($request, function () {
        return new Response('', 404);
    });

    expect(Error::query()->count())->toEqual(1);
    expect(count(Error::findByUrl('/abc')->hits ?? []))->toEqual(0);
    expect(Error::findByUrl('/abc')->hitsCount)->toEqual(1);
    // Still add count
    expect($response->status())->toEqual(404);
});

/** @test * */
function it_will_not_find_redirects_from_different_sites()
{
    Redirect::make()
        ->site('some-different-site')
        ->source('/abc')
        ->destination('/def')
        ->save();

    $response = $this->middleware->handle(Request::create('/abc'), function () {
        return new Response('', 404);
    });

    expect($response->isNotFound())->toBeTrue();
    expect(Error::query()->count())->toEqual(1);
}
