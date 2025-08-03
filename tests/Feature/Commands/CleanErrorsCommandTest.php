<?php

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Data\Error;

it('cleans errors older than 1 month by default', function () {
    Error::create(['url' => 'bla1'])->addHit(now()->subMonth()->subDay()->timestamp);
    Error::create(['url' => 'bla2'])->addHit(now()->subWeek()->subDay()->timestamp);
    Error::create(['url' => 'bla3'])->addHit(now()->timestamp);

    expect(Error::query()->count())->toEqual(3);

    Artisan::call(CleanErrorsCommand::class);

    expect(Error::query()->count())->toEqual(2);
});

it('looks at the config file for older than date', function () {
    config()->set('statamic.redirect.clean_older_than', '1 week');

    Error::create(['url' => 'bla'])->addHit(now()->subMonth()->subDay()->timestamp);
    Error::create(['url' => 'bla'])->addHit(now()->subWeek()->subDay()->timestamp);
    Error::create(['url' => 'bla'])->addHit(now()->timestamp);

    expect(Error::query()->count())->toEqual(3);

    Artisan::call(CleanErrorsCommand::class);

    expect(Error::query()->count())->toEqual(1);
});

it('doesnt clean errors if config is false', function () {
    config()->set('statamic.redirect.clean_errors', false);

    Error::create(['url' => 'bla'])->addHit(now()->subMonth()->subDay()->timestamp);
    Error::create(['url' => 'bla'])->addHit(now()->subWeek()->subDay()->timestamp);
    Error::create(['url' => 'bla'])->addHit(now()->timestamp);

    expect(Error::query()->count())->toEqual(3);

    Artisan::call(CleanErrorsCommand::class);

    expect(Error::query()->count())->toEqual(3);
});

it('deletes errors when there are too many', function () {
    config()->set('statamic.redirect.clean_errors', true);
    config()->set('statamic.redirect.keep_unique_errors', 1);

    Error::create(['url' => 'url1'])->addHit(now()->subDay()->timestamp);
    Error::create(['url' => 'url2'])->addHit(now()->subHour()->timestamp);
    Error::create(['url' => 'url3'])->addHit(now()->timestamp);

    expect(Error::query()->count())->toEqual(3);

    Artisan::call(CleanErrorsCommand::class);

    expect(Error::query()->count())->toEqual(1);
    expect(Error::findByUrl('url3'))->not->toBeNull();
});
