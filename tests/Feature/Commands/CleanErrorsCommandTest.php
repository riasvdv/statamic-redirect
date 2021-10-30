<?php

namespace Rias\StatamicRedirect\Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Facades\Error;
use Rias\StatamicRedirect\Tests\TestCase;

class CleanErrorsCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider repositories
     */
    public function it_cleans_errors_older_than_1_month_by_default($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        Error::make()->url('bla1')->addHit(now()->subMonth()->subDay()->timestamp)->save();
        Error::make()->url('bla2')->addHit(now()->subWeek()->subDay()->timestamp)->save();
        Error::make()->url('bla3')->addHit(now()->timestamp)->save();

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(2, Error::query()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_looks_at_the_config_file_for_older_than_date($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        config()->set('statamic.redirect.clean_older_than', '1 week');

        Error::make()->url('bla')->addHit(now()->subMonth()->subDay()->timestamp)->save();
        Error::make()->url('bla')->addHit(now()->subWeek()->subDay()->timestamp)->save();
        Error::make()->url('bla')->addHit(now()->timestamp)->save();

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(1, Error::query()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_doesnt_clean_errors_if_config_is_false($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        config()->set('statamic.redirect.clean_errors', false);

        Error::make()->url('bla')->addHit(now()->subMonth()->subDay()->timestamp)->save();
        Error::make()->url('bla')->addHit(now()->subWeek()->subDay()->timestamp)->save();
        Error::make()->url('bla')->addHit(now()->timestamp)->save();

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(3, Error::query()->count());
    }

    /**
     * @test
     * @dataProvider repositories
     */
    public function it_deletes_errors_when_there_are_too_many($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        config()->set('statamic.redirect.clean_errors', true);
        config()->set('statamic.redirect.keep_unique_errors', 1);

        Error::make()->url('url1')->addHit(now()->subDay()->timestamp)->save();
        Error::make()->url('url2')->addHit(now()->subHour()->timestamp)->save();
        Error::make()->url('url3')->addHit(now()->timestamp)->save();

        $this->assertEquals(3, Error::query()->count());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertEquals(1, Error::query()->count());
        $this->assertNotNull(Error::findByUrl('url3'));
    }
}
