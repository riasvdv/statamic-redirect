<?php

namespace Rias\StatamicRedirect\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Rias\StatamicRedirect\Models\Error;

class CleanErrorsCommand extends Command
{
    protected $signature = 'redirect:clean-errors';

    protected $description = 'Clean up old errors';

    public function handle()
    {
        if (! config('statamic.redirect.clean_errors', true)) {
            $this->info('Not cleaning errors. Change the config setting to enable cleaning.');
            return;
        }

        $this->info('Cleaning errors older than ' . config('statamic.redirect.clean_older_than', '1 month'));

        $olderThan = CarbonInterval::createFromDateString(config('statamic.redirect.clean_older_than', '1 month'));

        Error::all()
            ->filter(function (Error $error) use ($olderThan) {
                return Carbon::parse($error->date) < now()->sub($olderThan);
            })
            ->each(function (Error $error) {
                $error->delete();
            });

        $this->info('Done!');
    }
}
