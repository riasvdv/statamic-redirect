<?php

use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;

afterEach(function () {
    File::delete(base_path('netlify.toml'));
});

it('generates a netlify.toml file with exact redirects', function () {
    Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    Redirect::make()
        ->source('/temporary')
        ->destination('/other')
        ->type(302)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('netlify.toml'));

    expect($content)
        ->toContain('from = "/old-page"')
        ->toContain('to = "/new-page"')
        ->toContain('status = 301')
        ->toContain('from = "/temporary"')
        ->toContain('to = "/other"')
        ->toContain('status = 302');
});

it('converts 410 status to 301', function () {
    Redirect::make()
        ->source('/gone-page')
        ->destination('/')
        ->type(410)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('netlify.toml'));

    expect($content)
        ->toContain('from = "/gone-page"')
        ->toContain('status = 301')
        ->not->toContain('status = 410');
});

it('converts regex patterns to splat syntax', function () {
    Redirect::make()
        ->source('/blog/(.*)')
        ->destination('/news/$1')
        ->type(301)
        ->matchType(MatchTypeEnum::REGEX)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('netlify.toml'));

    expect($content)
        ->toContain('from = "/blog/*"')
        ->toContain('to = "/news/:splat"')
        ->not->toContain('(.*)')
        ->not->toContain('$1');
});

it('does not convert splat syntax for exact match redirects', function () {
    Redirect::make()
        ->source('/exact/(.*)')
        ->destination('/target/$1')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('netlify.toml'));

    expect($content)
        ->toContain('from = "/exact/(.*)"')
        ->toContain('to = "/target/$1"');
});

it('asks for confirmation when netlify.toml already exists', function () {
    File::put(base_path('netlify.toml'), 'existing content');

    Redirect::make()
        ->source('/old')
        ->destination('/new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->expectsConfirmation('netlify.toml already exists. Do you want to overwrite it?', 'no')
        ->assertSuccessful();

    expect(File::get(base_path('netlify.toml')))->toBe('existing content');
});

it('overwrites netlify.toml when confirmed', function () {
    File::put(base_path('netlify.toml'), 'existing content');

    Redirect::make()
        ->source('/old')
        ->destination('/new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->expectsConfirmation('netlify.toml already exists. Do you want to overwrite it?', 'yes')
        ->assertSuccessful();

    expect(File::get(base_path('netlify.toml')))
        ->toContain('from = "/old"')
        ->toContain('to = "/new"');
});

it('generates valid TOML redirect blocks', function () {
    Redirect::make()
        ->source('/source')
        ->destination('/destination')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-netlify-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('netlify.toml'));

    expect($content)->toContain(<<<'TOML'
    [[redirects]]
    from = "/source"
    to = "/destination"
    status = 301
    TOML);
});
