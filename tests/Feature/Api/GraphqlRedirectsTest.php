<?php

use Rias\StatamicRedirect\Facades\Redirect;

it('can query redirects via graphql', function () {
    Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->enabled(true)
        ->order(0)
        ->save();

    Redirect::make()
        ->source('/another-old')
        ->destination('/another-new')
        ->type(302)
        ->enabled(true)
        ->order(1)
        ->save();

    $query = <<<'GRAPHQL'
    {
        redirects {
            data {
                source
                destination
                type
                enabled
            }
        }
    }
    GRAPHQL;

    $response = $this->post('/graphql', ['query' => $query]);

    $response->assertOk();
    $response->assertJsonPath('data.redirects.data.0.source', '/old-page');
    $response->assertJsonPath('data.redirects.data.0.destination', '/new-page');
    $response->assertJsonPath('data.redirects.data.0.type', 301);
    $response->assertJsonPath('data.redirects.data.0.enabled', true);
    $response->assertJsonCount(2, 'data.redirects.data');
});

it('can query a single redirect via graphql', function () {
    $redirect = Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->enabled(true);
    $redirect->save();

    $query = <<<GRAPHQL
    {
        redirect(id: "{$redirect->id()}") {
            source
            destination
            type
        }
    }
    GRAPHQL;

    $response = $this->post('/graphql', ['query' => $query]);

    $response->assertOk();
    $response->assertJsonPath('data.redirect.source', '/old-page');
    $response->assertJsonPath('data.redirect.destination', '/new-page');
    $response->assertJsonPath('data.redirect.type', 301);
});

it('can filter redirects by site via graphql', function () {
    Redirect::make()
        ->source('/default-page')
        ->destination('/new-page')
        ->site('default')
        ->save();

    Redirect::make()
        ->source('/other-page')
        ->destination('/another-new')
        ->site('other')
        ->save();

    $query = <<<'GRAPHQL'
    {
        redirects(site: "default") {
            data {
                source
                site
            }
        }
    }
    GRAPHQL;

    $response = $this->post('/graphql', ['query' => $query]);

    $response->assertOk();
    $response->assertJsonCount(1, 'data.redirects.data');
    $response->assertJsonPath('data.redirects.data.0.source', '/default-page');
    $response->assertJsonPath('data.redirects.data.0.site', 'default');
});

it('can filter redirects by enabled status via graphql', function () {
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

    $query = <<<'GRAPHQL'
    {
        redirects(enabled: true) {
            data {
                source
                enabled
            }
        }
    }
    GRAPHQL;

    $response = $this->post('/graphql', ['query' => $query]);

    $response->assertOk();
    $response->assertJsonCount(1, 'data.redirects.data');
    $response->assertJsonPath('data.redirects.data.0.source', '/enabled-page');
});

it('can limit redirects via graphql', function () {
    for ($i = 0; $i < 10; $i++) {
        Redirect::make()
            ->source("/page-{$i}")
            ->destination("/new-page-{$i}")
            ->order($i)
            ->save();
    }

    $query = <<<'GRAPHQL'
    {
        redirects(limit: 3) {
            data {
                source
            }
        }
    }
    GRAPHQL;

    $response = $this->post('/graphql', ['query' => $query]);

    $response->assertOk();
    $response->assertJsonCount(3, 'data.redirects.data');
});
