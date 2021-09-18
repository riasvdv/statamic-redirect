<?php

namespace Rias\StatamicRedirect\Jobs;

use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
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
        $olderThan = CarbonInterval::createFromDateString(config('statamic.redirect.clean_older_than', '1 month'));

        ErrorFacade::all()
            ->each(function (Error $error) use ($olderThan) {
                $originalHits = $error->hits() ?? [];

                $hits = array_filter($originalHits, function (array $hit) use ($olderThan) {
                    return Carbon::parse($hit['timestamp']) > now()->sub($olderThan);
                });

                if (! count($hits) && config('statamic.redirect.log_hits', true)) {
                    $error->delete();

                    return;
                }

                if (count($originalHits) !== count($hits)) {
                    $error->hits($hits);
                    $error->save();
                }
            });

        if (ErrorFacade::all()->count() <= config('statamic.redirect.keep_unique_errors')) {
            return;
        }

        $errorsToDelete = ErrorFacade::all()->count() - config('statamic.redirect.keep_unique_errors');

        ErrorFacade::all()
            ->sortBy(function (Error $error) {
                $latestHit = collect($error->hits() ?? [])->sortByDesc('timestamp')->first();

                if (! $latestHit) {
                    return null;
                }

                return $latestHit['timestamp'];
            })
            ->take($errorsToDelete)
            ->each(function (Error $error) {
                $error->delete();
            });

        Stache::refresh();
    }
}
