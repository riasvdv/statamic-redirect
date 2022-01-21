<?php

namespace Rias\StatamicRedirect\Commands;

use Illuminate\Console\Command;
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

        (new CleanErrorsJob())->handle();

        $this->info('Done!');
    }
}
