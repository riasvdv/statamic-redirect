<?php

use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;

afterEach(function () {
    File::delete(base_path('vercel.json'));
});

it('generates a vercel.json file with exact redirects', function () {
    Redirect::make()
        ->source('/old-page')
        ->destination('/new-page')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    Redirect::make()
        ->source('/temporary')
        ->destination('/other')
        ->type(302)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config['redirects'])->toHaveCount(2)
        ->and($config['redirects'][0])->toBe([
            'source' => '/old-page',
            'destination' => '/new-page',
            'statusCode' => 301,
        ])
        ->and($config['redirects'][1])->toBe([
            'source' => '/temporary',
            'destination' => '/other',
            'statusCode' => 302,
        ]);
});

it('converts 410 status to 301', function () {
    Redirect::make()
        ->source('/gone-page')
        ->destination('/')
        ->type(410)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config['redirects'][0]['statusCode'])->toBe(301);
});

it('converts regex patterns to path syntax', function () {
    Redirect::make()
        ->source('/blog/(.*)')
        ->destination('/news/$1')
        ->type(301)
        ->matchType(MatchTypeEnum::REGEX)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config['redirects'][0]['source'])->toBe('/blog/:path*')
        ->and($config['redirects'][0]['destination'])->toBe('/news/:path*');
});

it('does not convert path syntax for exact match redirects', function () {
    Redirect::make()
        ->source('/exact/(.*)')
        ->destination('/target/$1')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config['redirects'][0]['source'])->toBe('/exact/(.*)')
        ->and($config['redirects'][0]['destination'])->toBe('/target/$1');
});

it('asks for confirmation when vercel.json already exists', function () {
    File::put(base_path('vercel.json'), json_encode(['buildCommand' => 'npm run build']));

    Redirect::make()
        ->source('/old')
        ->destination('/new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->expectsConfirmation('vercel.json already exists. Do you want to update the redirects?', 'no')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config)->not->toHaveKey('redirects')
        ->and($config['buildCommand'])->toBe('npm run build');
});

it('merges redirects into existing vercel.json when confirmed', function () {
    File::put(base_path('vercel.json'), json_encode(['buildCommand' => 'npm run build']));

    Redirect::make()
        ->source('/old')
        ->destination('/new')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->expectsConfirmation('vercel.json already exists. Do you want to update the redirects?', 'yes')
        ->assertSuccessful();

    $config = json_decode(File::get(base_path('vercel.json')), true);

    expect($config['buildCommand'])->toBe('npm run build')
        ->and($config['redirects'])->toHaveCount(1)
        ->and($config['redirects'][0]['source'])->toBe('/old');
});

it('generates valid json with unescaped slashes', function () {
    Redirect::make()
        ->source('/source')
        ->destination('/destination')
        ->type(301)
        ->matchType(MatchTypeEnum::EXACT)
        ->enabled(true)
        ->save();

    $this->artisan('redirect:generate-vercel-redirects')
        ->assertSuccessful();

    $raw = File::get(base_path('vercel.json'));

    expect($raw)->toContain('/source')
        ->toContain('/destination')
        ->not->toContain('\\/');

    $config = json_decode($raw, true);

    expect($config)->not->toBeNull();
});
