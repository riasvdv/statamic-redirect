<?php

namespace Rias\StatamicRedirect\Widgets;

use Illuminate\Contracts\View\View;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Http\Controllers\DashboardController;
use Statamic\Widgets\Widget;

final class ErrorsLastMonthWidget extends Widget
{
    public function html(): View
    {
        $data = $this->getStatsPastMonth();
        $title = $this->config('title', __('Errors last month'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }

    private function getStatsPastMonth(): array
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
}
