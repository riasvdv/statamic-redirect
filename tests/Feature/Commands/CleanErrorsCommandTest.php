<?php

namespace Rias\StatamicRedirect\Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Models\Error;
use Rias\StatamicRedirect\Tests\TestCase;

class CleanErrorsCommandTest extends TestCase
{
    /** @test * */
    public function it_cleans_errors_older_than_1_month_by_default()
    {
        Error::create(['url' => 'bla', 'date' => now()->subMonth()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()->subWeek()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()]);

        $this->assertCount(3, Error::all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(2, Error::all());
    }

    /** @test * */
    public function it_looks_at_the_config_file_for_older_than_date()
    {
        config()->set('statamic.redirect.clean_older_than', '1 week');

        Error::create(['url' => 'bla', 'date' => now()->subMonth()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()->subWeek()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()]);

        $this->assertCount(3, Error::all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(1, Error::all());
    }

    /** @test * */
    public function it_doesnt_clean_errors_if_config_is_false()
    {
        config()->set('statamic.redirect.clean_errors', false);

        Error::create(['url' => 'bla', 'date' => now()->subMonth()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()->subWeek()->subDay()]);
        Error::create(['url' => 'bla', 'date' => now()]);

        $this->assertCount(3, Error::all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(3, Error::all());
    }
}
