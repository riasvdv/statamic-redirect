<?php

namespace Rias\StatamicRedirect\Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Tests\TestCase;

class CleanErrorsCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_cleans_errors_older_than_1_month_by_default()
    {
        Error::create(['url' => 'bla1'])->addHit(now()->subMonth()->subDay()->timestamp);
        Error::create(['url' => 'bla2'])->addHit(now()->subWeek()->subDay()->timestamp);
        Error::create(['url' => 'bla3'])->addHit(now()->timestamp);

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(2, Error::query()->count());
    }

    /**
     * @test
     */
    public function it_looks_at_the_config_file_for_older_than_date()
    {
        config()->set('statamic.redirect.clean_older_than', '1 week');

        Error::create(['url' => 'bla'])->addHit(now()->subMonth()->subDay()->timestamp);
        Error::create(['url' => 'bla'])->addHit(now()->subWeek()->subDay()->timestamp);
        Error::create(['url' => 'bla'])->addHit(now()->timestamp);

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(1, Error::query()->count());
    }

    /**
     * @test
     */
    public function it_doesnt_clean_errors_if_config_is_false()
    {
        config()->set('statamic.redirect.clean_errors', false);

        Error::create(['url' => 'bla'])->addHit(now()->subMonth()->subDay()->timestamp);
        Error::create(['url' => 'bla'])->addHit(now()->subWeek()->subDay()->timestamp);
        Error::create(['url' => 'bla'])->addHit(now()->timestamp);

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(3, Error::query()->count());
    }

    /**
     * @test
     */
    public function it_deletes_errors_when_there_are_too_many()
    {
        config()->set('statamic.redirect.clean_errors', true);
        config()->set('statamic.redirect.keep_unique_errors', 1);

        Error::create(['url' => 'url1'])->addHit(now()->subDay()->timestamp);
        Error::create(['url' => 'url2'])->addHit(now()->subHour()->timestamp);
        Error::create(['url' => 'url3'])->addHit(now()->timestamp);

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(1, Error::query()->count());
        $this->assertNotNull(Error::findByUrl('url3'));
    }
}
