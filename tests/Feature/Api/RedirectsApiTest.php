<?php

use Rias\StatamicRedirect\Facades\Redirect;

beforeEach(function () {
    config()->set('statamic.api.enabled', true);
});

it('can list redirects', function () {
    Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->enabled(true)
        ->save();

    Redirect::make()
        ->source('/another-old')
        ->destination('/another-new')
        ->type(302)
        ->enabled(true)
        ->save();

    $response = $this->get('/api/redirects');

    $response->assertOk();
    $response->assertJsonCount(2, 'data');
    $response->assertJsonPath('data.0.source', '/old-page');
    $response->assertJsonPath('data.0.destination', '/new-page');
    $response->assertJsonPath('data.0.type', 301);
});

it('can get a single redirect', function () {
    $redirect = Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->enabled(true);
    $redirect->save();

    $response = $this->get("/api/redirects/{$redirect->id()}");

    $response->assertOk();
    $response->assertJsonPath('data.source', '/old-page');
    $response->assertJsonPath('data.destination', '/new-page');
    $response->assertJsonPath('data.type', 301);
});

it('returns 404 for non-existent redirect', function () {
    $response = $this->get('/api/redirects/non-existent-id');

    $response->assertNotFound();
});

it('can filter redirects by site', function () {
    Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->site('default')
        ->save();

    Redirect::make()
        ->source('/another-old')
        ->destination('/another-new')
        ->site('other')
        ->save();

    $response = $this->get('/api/redirects?site=default');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.source', '/old-page');
});

it('can filter redirects by enabled status', function () {
    Redirect::make()
        ->source('/enabled-page')
        ->destination('/new-page')
        ->enabled(true)
        ->save();

    Redirect::make()
        ->source('/disabled-page')
        ->destination('/another-new')
        ->enabled(false)
        ->save();

    $response = $this->get('/api/redirects?enabled=true');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.source', '/enabled-page');
});

it('can filter redirects by match type', function () {
    Redirect::make()
        ->source('/exact-page')
        ->destination('/new-page')
        ->matchType('exact')
        ->save();

    Redirect::make()
        ->source('/regex-.*')
        ->destination('/another-new')
        ->matchType('regex')
        ->save();

    $response = $this->get('/api/redirects?match_type=exact');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.source', '/exact-page');
});

it('can filter redirects by type', function () {
    Redirect::make()
        ->source('/permanent')
        ->destination('/new-page')
        ->type(301)
        ->save();

    Redirect::make()
        ->source('/temporary')
        ->destination('/another-new')
        ->type(302)
        ->save();

    $response = $this->get('/api/redirects?type=301');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.source', '/permanent');
});

it('can paginate redirects', function () {
    for ($i = 0; $i < 15; $i++) {
        Redirect::make()
            ->source("/page-{$i}")
            ->destination("/new-page-{$i}")
            ->order($i)
            ->save();
    }

    $response = $this->get('/api/redirects?limit=5');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
    $response->assertJsonPath('meta.total', 15);
    $response->assertJsonPath('meta.per_page', 5);
});
