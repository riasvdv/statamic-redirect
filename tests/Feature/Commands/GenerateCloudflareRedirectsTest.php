<?php

use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;

afterEach(function () {
    File::delete(base_path('_redirects'));
});

it('generates a _redirects file with exact redirects', function () {
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

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));

    expect($content)
        ->toContain('/old-page /new-page 301')
        ->toContain('/temporary /other 302');
});

it('converts 410 status to 301', function () {
    Redirect::make()
        ->source('/gone-page')
        ->destination('/')
        ->type(410)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));

    expect($content)
        ->toContain('/gone-page / 301')
        ->not->toContain('410');
});

it('converts regex patterns to splat syntax', function () {
    Redirect::make()
        ->source('/blog/(.*)')
        ->destination('/news/$1')
        ->type(301)
        ->matchType(MatchTypeEnum::REGEX)
        ->save();

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));

    expect($content)
        ->toContain('/blog/* /news/:splat 301')
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

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));

    expect($content)->toContain('/exact/(.*) /target/$1 301');
});

it('overwrites existing _redirects file without confirmation', function () {
    File::put(base_path('_redirects'), 'existing content');

    Redirect::make()
        ->source('/old')
        ->destination('/new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));

    expect($content)
        ->toContain('/old /new 301')
        ->not->toContain('existing content');
});

it('generates one redirect per line', function () {
    Redirect::make()
        ->source('/first')
        ->destination('/first-new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    Redirect::make()
        ->source('/second')
        ->destination('/second-new')
        ->type(302)
        ->matchType(MatchTypeEnum::EXACT)
        ->save();

    $this->artisan('redirect:generate-cloudflare-redirects')
        ->assertSuccessful();

    $content = File::get(base_path('_redirects'));
    $lines = array_filter(explode("\n", trim($content)));

    expect($lines)->toHaveCount(2);
});
