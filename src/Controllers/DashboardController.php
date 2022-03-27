<?php

namespace Rias\StatamicRedirect\Controllers;

use Exception;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Hit;
use Statamic\Facades\Scope;
use Statamic\Facades\User;

class DashboardController
{
    public function __invoke()
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('view redirects'), 401);

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
        ]);
    }

    private function getStatsPastMonth()
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

    private function getStatsPastWeek()
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

    private function getStatsPastDay()
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
