<?php

namespace Rias\StatamicRedirect\Jobs;

use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Facades\Error as ErrorFacade;
use Statamic\Facades\Stache;

class CleanErrorsJob
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        File::put(storage_path('redirect/clean_last_ran_at.txt'), now()->timestamp);

        $olderThan = CarbonInterval::createFromDateString(config('statamic.redirect.clean_older_than', '1 month'));

        if (config('statamic.redirect.log_hits', true)) {
            ErrorFacade::all()
                ->each(function (Error $error) use ($olderThan) {
                    $originalHits = $error->hits() ?? [];

                    $hits = array_filter($originalHits, function (array $hit) use ($olderThan) {
                        return Carbon::parse($hit['timestamp']) > now()->sub($olderThan);
                    });

                    if (! count($hits)) {
                        $error->delete();
                        Stache::store('redirects')->clear();

                        return;
                    }

                    if (count($originalHits) !== count($hits)) {
                        $error->setHits($hits);
                        $error->save();
                    }
                });
        }

        $allErrors = ErrorFacade::all();
        $errorCount = $allErrors->count();
        if ($errorCount <= config('statamic.redirect.keep_unique_errors')) {
            Stache::store('redirects')->clear();
            return;
        }

        $errorsToDelete = $errorCount - config('statamic.redirect.keep_unique_errors');

        $allErrors
            ->sortBy(function (Error $error) {
                return $error->latest();
            })
            ->take($errorsToDelete)
            ->each(function (Error $error) {
                $error->delete();
            });

        Stache::store('redirects')->clear();
    }
}
