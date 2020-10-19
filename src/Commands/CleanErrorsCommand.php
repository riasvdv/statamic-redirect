<?php

namespace Rias\StatamicRedirect\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Facades\Error as ErrorFacade;
use Rias\StatamicRedirect\Jobs\CleanErrorsJob;
use Statamic\Console\RunsInPlease;

class CleanErrorsCommand extends Command
{
    use RunsInPlease;

    protected $signature = 'redirect:clean-errors';

    protected $description = 'Clean up old errors';

    public function handle()
    {
        if (! config('statamic.redirect.clean_errors', true)) {
            $this->info('Not cleaning errors. Change the config setting to enable cleaning.');

            return;
        }

        $this->info('Cleaning errors older than ' . config('statamic.redirect.clean_older_than', '1 month'));

        CleanErrorsJob::dispatchNow();

        $this->info('Done!');
    }
}
