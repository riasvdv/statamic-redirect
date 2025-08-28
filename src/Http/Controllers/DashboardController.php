<?php

namespace Rias\StatamicRedirect\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Contracts\Redirect;
use Rias\StatamicRedirect\Data\Hit;
use Statamic\Facades\Scope;

class DashboardController
{
    use AuthorizesRequests;

    public function __invoke(): View
    {
        $this->authorize('view', Redirect::class);

        $notFoundMonth = $this->getStatsPastMonth();
        $notFoundWeek = $this->getStatsPastWeek();
        $notFoundDay = $this->getStatsPastDay();

        $cleanupLastRanAt = null;

        try {
            $cleanupLastRanAt = File::get(storage_path('redirect/clean_last_ran_at.txt'));
        } catch (Exception $e) {
            // Do nothing
        }

        return view('redirect::index', [
            'notFoundMonth' => $notFoundMonth,
            'notFoundWeek' => $notFoundWeek,
            'notFoundDay' => $notFoundDay,
            'filters' => Scope::filters('errors'),
            'cleanupLastRanAt' => $cleanupLastRanAt,
            'actionUrl' => cp_route('redirect.errors.actions.run'),
        ]);
    }

    public function getStatsPastMonth(): array
    {
        $weeks = [];
        for ($week = now()->subWeeks(4); $week < now(); $week->addWeek()) {
            $weeks[] = $week->copy();
        }

        $notFoundMonth = [];
        foreach ($weeks as $week) {
            $count = Hit::whereBetween('timestamp', [$week->startOfWeek()->timestamp, $week->endOfWeek()->timestamp])->count();

            $notFoundMonth[] = [$count, "{$week->startOfWeek()->format('d')}-{$week->endOfWeek()->format('d')}"];
        }

        return $notFoundMonth;
    }

    public function getStatsPastWeek(): array
    {
        $days = [];
        for ($day = now()->subWeek(); $day < now(); $day->addDay()) {
            $days[] = $day->copy();
        }

        $notFoundWeek = [];
        foreach ($days as $day) {
            $count = Hit::whereBetween('timestamp', [$day->startOfDay()->timestamp, $day->endOfDay()->timestamp])->count();
            $notFoundWeek[] = [$count, $day->format('d')];
        }

        return $notFoundWeek;
    }

    public function getStatsPastDay(): array
    {
        $hours = [];
        for ($hour = now()->subDay(); $hour < now(); $hour->addHours(4)) {
            $hours[] = $hour->copy();
        }

        $notFoundDay = [];
        foreach ($hours as $hour) {
            $count = Hit::whereBetween('timestamp', [$hour->startOfHour()->timestamp, $hour->endOfHour()->timestamp])->count();
            $notFoundDay[] = [$count, $hour->format('H:00')];
        }

        return $notFoundDay;
    }
}
