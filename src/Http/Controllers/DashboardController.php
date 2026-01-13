<?php

namespace Rias\StatamicRedirect\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;
use Rias\StatamicRedirect\Contracts\Redirect;
use Rias\StatamicRedirect\Widgets\ErrorsLastDayWidget;
use Rias\StatamicRedirect\Widgets\ErrorsLastMonthWidget;
use Rias\StatamicRedirect\Widgets\ErrorsLastWeekWidget;
use Rias\StatamicRedirect\Widgets\ErrorsWidget;
use Rias\StatamicRedirect\Widgets\TopErrorsWidget;
use Throwable;

class DashboardController
{
    use AuthorizesRequests;

    public function __invoke(
        ErrorsLastMonthWidget $errorsLastMonthWidget,
        ErrorsLastWeekWidget $errorsLastWeekWidget,
        ErrorsLastDayWidget $errorsLastDayWidget,
        ErrorsWidget $errorsWidget,
        TopErrorsWidget $topErrorsWidget,
    ): Response {
        $this->authorize('view', Redirect::class);

        $cleanupLastRanAt = null;

        try {
            $cleanupLastRanAt = File::get(storage_path('redirect/clean_last_ran_at.txt'));
        } catch (Throwable) {
            // Do nothing
        }

        $cleanupHasRun = app()->environment('local')
            || ! $cleanupLastRanAt
            || Carbon::createFromTimestamp($cleanupLastRanAt) > now()->subDays(2);

        return Inertia::render('redirect::Dashboard', [
            'enabled' => config('statamic.redirect.enable'),
            'logHits' => config('statamic.redirect.log_hits'),
            'widgets' => [
                'errorsLastMonth' => (string) $errorsLastMonthWidget->html(),
                'errorsLastWeek' => (string) $errorsLastWeekWidget->html(),
                'errorsLastDay' => (string) $errorsLastDayWidget->html(),
                'errors' => (string) $errorsWidget->html(),
                'topErrors' => (string) $topErrorsWidget->setConfig(['actions' => false])->html(),
            ],
            'cleanupHasRun' => $cleanupHasRun,
            'cleanupLastRanAtHuman' => $cleanupHasRun && $cleanupLastRanAt
                ? Carbon::createFromTimestamp($cleanupLastRanAt)->diffForHumans(syntax: 1)
                : '2 days',
            'actionUrl' => cp_route('redirect.errors.actions.run'),
        ]);
    }
}
