<?php

namespace Rias\StatamicRedirect\Jobs;

use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;

class CleanErrorsJob
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        File::ensureDirectoryExists(storage_path('redirect'));

        File::put(storage_path('redirect/clean_last_ran_at.txt'), now()->timestamp);

        $olderThan = CarbonInterval::createFromDateString(config('statamic.redirect.clean_older_than', '1 month'));

        if (config('statamic.redirect.log_hits', true)) {
            Hit::query()->where('timestamp', '<', now()->sub($olderThan)->timestamp)->delete();
            Error::query()->whereDoesntHave('hits')->delete();
        }

        $errorCount = Error::count();
        if ($errorCount <= config('statamic.redirect.keep_unique_errors')) {
            return;
        }

        $errorsToDelete = $errorCount - config('statamic.redirect.keep_unique_errors');

        Error::query()
            ->orderBy('lastSeenAt')
            ->limit($errorsToDelete)
            ->delete();
    }
}
