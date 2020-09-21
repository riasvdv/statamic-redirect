<?php

namespace Rias\StatamicRedirect\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Facades\Error as ErrorFacade;

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

        ErrorFacade::all()
            ->each(function (Error $error) use ($olderThan) {
                $originalHits = $error->hits();

                $hits = array_filter($originalHits, function (array $hit) use ($olderThan) {
                    return Carbon::parse($hit['timestamp']) > now()->sub($olderThan);
                });

                if (! count($hits)) {
                    $error->delete();
                    return;
                }

                if (count($originalHits) !== count($hits)) {
                    $error->hits($hits);
                    $error->save();
                }
            });

        $this->info('Done!');
    }
}
