<?php

namespace Rias\StatamicRedirect\Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\Repositories\FileErrorRepository;
use Rias\StatamicRedirect\Tests\TestCase;

class CleanErrorsCommandTest extends TestCase
{
    /** @var FileErrorRepository */
    private $errorRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->errorRepository = app(FileErrorRepository::class);
    }

    /** @test * */
    public function it_cleans_errors_older_than_1_month_by_default()
    {
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subMonth()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subWeek()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->timestamp]));

        $this->assertCount(3, $this->errorRepository->all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(2, $this->errorRepository->all());
    }

    /** @test * */
    public function it_looks_at_the_config_file_for_older_than_date()
    {
        config()->set('statamic.redirect.clean_older_than', '1 week');

        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subMonth()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subWeek()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->timestamp]));

        $this->assertCount(3, $this->errorRepository->all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(1, $this->errorRepository->all());
    }

    /** @test * */
    public function it_doesnt_clean_errors_if_config_is_false()
    {
        config()->set('statamic.redirect.clean_errors', false);

        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subMonth()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->subWeek()->subDay()->timestamp]));
        $this->errorRepository->save(new Error(['url' => 'bla', 'date' => now()->timestamp]));

        $this->assertCount(3, $this->errorRepository->all());

        Artisan::call(CleanErrorsCommand::class);

        $this->assertCount(3, $this->errorRepository->all());
    }
}
