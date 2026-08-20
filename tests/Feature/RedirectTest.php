<?php

use Illuminate\Support\Facades\Event;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\UpdateScripts\PreserveEntryDestinations;
use Statamic\Facades\Entry;
use Statamic\Facades\Site;

it('dispatches an event when saving', function () {
    Event::fake();

    Redirect::make()->save();

    Event::assertDispatched(RedirectSaved::class);
});

it('redirects to the selected entry localization', function () {
    $french = Mockery::mock(Statamic\Contracts\Entries\Entry::class);
    $french->shouldReceive('url')->once()->andReturn('/fr/french-page');
    Entry::shouldReceive('find')->once()->with('page-fr')->andReturn($french);

    $redirect = Redirect::make()
        ->site('en')
        ->destination('entry::page-fr');

    expect($redirect->destination())->toBe('/fr/french-page');
    expect($redirect->destination_type())->toBe('entry');
    expect($redirect->destination_entry())->toBe('page-fr');
});

it('preserves existing entry redirect behavior during the update', function () {
    Site::setSites([
        'en' => ['name' => 'English', 'url' => '/', 'locale' => 'en_US'],
        'fr' => ['name' => 'French', 'url' => '/fr/', 'locale' => 'fr_FR'],
    ]);

    $english = Mockery::mock(Statamic\Contracts\Entries\Entry::class);
    $english->shouldReceive('id')->andReturn('page-en');
    $english->shouldReceive('in')->once()->with('fr')->andReturn($french = Mockery::mock(Statamic\Contracts\Entries\Entry::class));
    $french->shouldReceive('id')->andReturn('page-fr');

    Entry::shouldReceive('find')->with('page-en')->andReturn($english);

    $redirect = Redirect::make()
        ->id('existing-redirect')
        ->site('fr')
        ->source('/old-page')
        ->destination_type('entry')
        ->destination_entry('page-en');
    $redirect->save();

    (new PreserveEntryDestinations('rias/statamic-redirect'))->update();

    $redirect = Redirect::find('existing-redirect');

    expect($redirect->rawDestination())->toBe('entry::page-fr');

    $redirect
        ->destination('/replacement-page')
        ->destination_type('url')
        ->destination_entry(null)
        ->save();

    expect(Redirect::find('existing-redirect')->destination())->toBe('/replacement-page');
});

it('keeps an entry in its existing site when the redirect site has no localization', function () {
    Site::setSites([
        'en' => ['name' => 'English', 'url' => '/', 'locale' => 'en_US'],
        'fr' => ['name' => 'French', 'url' => '/fr/', 'locale' => 'fr_FR'],
    ]);

    $english = Mockery::mock(Statamic\Contracts\Entries\Entry::class);
    $english->shouldReceive('id')->andReturn('page-en');
    $english->shouldReceive('in')->once()->with('fr')->andReturnNull();

    Entry::shouldReceive('find')->with('page-en')->andReturn($english);

    Redirect::make()
        ->id('existing-redirect')
        ->site('fr')
        ->source('/old-page')
        ->destination_type('entry')
        ->destination_entry('page-en')
        ->save();

    (new PreserveEntryDestinations('rias/statamic-redirect'))->update();

    expect(Redirect::find('existing-redirect')->rawDestination())->toBe('entry::page-en');
});

it('does not relocalize native link destinations during the update', function () {
    Entry::shouldReceive('find')->never();

    Redirect::make()
        ->id('cross-site-redirect')
        ->site('en')
        ->source('/old-page')
        ->destination('entry::page-fr')
        ->save();

    (new PreserveEntryDestinations('rias/statamic-redirect'))->update();

    expect(Redirect::find('cross-site-redirect')->rawDestination())->toBe('entry::page-fr');
});

it('uses the native cross-site link field without an editable site field', function () {
    $fields = (new RedirectBlueprint)()->fields();

    expect($fields->get('destination')->config())
        ->toMatchArray([
            'type' => 'link',
            'select_across_sites' => true,
        ])
        ->and($fields->get('site'))->toBeNull();
});

it('uses the selected site on creation and preserves it on update', function () {
    Site::setSites([
        'en' => ['name' => 'English', 'url' => '/', 'locale' => 'en_US'],
        'fr' => ['name' => 'French', 'url' => '/fr/', 'locale' => 'fr_FR'],
    ]);

    $this->asAdmin();
    Site::setSelected('fr');

    $response = $this->post(cp_route('redirect.redirects.store'), [
        'source' => '/old-page',
        'destination' => '/new-page',
        'enabled' => true,
        'type' => 301,
        'match_type' => 'exact',
    ])->assertOk();

    $redirect = Redirect::find($response->json('data.id'));

    expect($redirect->site())->toBe('fr');

    Site::setSelected('en');

    $this->post(cp_route('redirect.redirects.update', $redirect->id()), [
        'source' => '/old-page',
        'destination' => '/updated-page',
        'enabled' => true,
        'type' => 301,
        'match_type' => 'exact',
        'site' => ['en'],
    ])->assertOk();

    expect(Redirect::find($redirect->id())->site())->toBe('fr');
});

it('lists redirects for the globally selected site', function () {
    Site::setSites([
        'en' => ['name' => 'English', 'url' => '/', 'locale' => 'en_US'],
        'fr' => ['name' => 'French', 'url' => '/fr/', 'locale' => 'fr_FR'],
    ]);

    Redirect::make()
        ->id('english-redirect')
        ->site('en')
        ->source('/english')
        ->destination('/english-new')
        ->save();

    Redirect::make()
        ->id('french-redirect')
        ->site('fr')
        ->source('/french')
        ->destination('/french-new')
        ->save();

    $this->asAdmin();
    Site::setSelected('fr');

    $this->getJson(cp_route('redirect.api.redirects.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', 'french-redirect');
});

it('registers the entry destination update script', function () {
    expect(app('statamic.update-scripts')->pluck('class'))->toContain(PreserveEntryDestinations::class);
});
