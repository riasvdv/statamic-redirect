<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Rias\StatamicRedirect\Models\Error;
use Statamic\CP\Column;

class DashboardController
{
    public function __invoke()
    {
        $errors = Error::all()->toArray();

        $notFoundMonth = Cache::remember('redirect-stats-past-Month', now()->addHour(), function () use ($errors) {
            return $this->getStatsPastMonth($errors);
        });
        $notFoundWeek = Cache::remember('redirect-stats-past-Week', now()->addHour(), function () use ($errors) {
            return $this->getStatsPastWeek($errors);
        });
        $notFoundDay = Cache::remember('redirect-stats-past-Day', now()->addHour(), function () use ($errors) {
            return $this->getStatsPastDay($errors);
        });

        $errors = collect($errors)->groupBy(function (array $error) {
            return $error['url'];
        })->map(function ($errors, $url) {
            return [
                'hits' => $errors->count(),
                'url' => $url,
                'latest' => $errors->last()['date'],
                'handled' => $errors->last()['handled'],
            ];
        })->sortByDesc('latest')->values();

        return view('redirect::index', [
            'errors' => $errors,
            'notFoundMonth' => $notFoundMonth,
            'notFoundWeek' => $notFoundWeek,
            'notFoundDay' => $notFoundDay,
            'columns'  => [
                Column::make('url')->label('Path'),
                Column::make('hits')->label('Hits'),
                Column::make('latest')->label('Latest error'),
                Column::make('handled')->label('Handled'),
            ],
        ]);
    }

    private function getStatsPastMonth(array $errors)
    {
        $newerThan = now()->subMonth()->timestamp;
        $errors = array_filter($errors, function (array $error) use ($newerThan) {
            return $error['date'] > $newerThan;
        });

        $days = [];
        for ($day = now()->subWeeks(4); $day < now(); $day->addWeek()) {
            $days[] = $day->copy();
        }

        $notFoundMonth = [];
        foreach ($days as $day) {
            $count = count(array_filter($errors, function (array $error) use ($day) {
                return Date::parse($error['date'])->isSameWeek($day);
            }));
            $notFoundMonth[] = [$count, "{$day->startOfWeek()->format('d')}-{$day->endOfWeek()->format('d')}"];
        }

        return $notFoundMonth;
    }

    private function getStatsPastWeek(array $errors)
    {
        $newerThan = now()->subWeek()->timestamp;
        $errors = array_filter($errors, function (array $error) use ($newerThan) {
            return $error['date'] > $newerThan;
        });

        $days = [];
        for ($day = now()->subWeek(); $day < now(); $day->addDay()) {
            $days[] = $day->copy();
        }

        $notFoundWeek = [];
        foreach ($days as $day) {
            $count = count(array_filter($errors, function (array $error) use ($day) {
                $date = Date::parse($error['date']);
                return $date->isSameYear($day) && $date->isSameMonth($day) && $date->isSameDay($day);
            }));
            $notFoundWeek[] = [$count, $day->format('d')];
        }

        return $notFoundWeek;
    }

    private function getStatsPastDay(array $errors)
    {
        $newerThan = now()->subDay()->timestamp;
        $errors = array_filter($errors, function (array $error) use ($newerThan) {
            return $error['date'] > $newerThan;
        });

        $hours = [];
        for ($hour = now()->subDay(); $hour < now(); $hour->addHours(4)) {
            $hours[] = $hour->copy();
        }

        $notFoundDay = [];
        foreach ($hours as $hour) {
            $count = count(array_filter($errors, function (array $error) use ($hour) {
                return Date::parse($error['date'])->isBetween($hour->copy()->subHour(), $hour->copy()->addHours(3));
            }));
            $notFoundDay[] = [$count, $hour->format('H:00')];
        }

        return $notFoundDay;
    }
}
