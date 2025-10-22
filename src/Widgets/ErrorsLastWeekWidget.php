<?php

namespace Rias\StatamicRedirect\Widgets;

use Illuminate\Contracts\View\View;
use Rias\StatamicRedirect\Data\Hit;
use Statamic\Widgets\Widget;

final class ErrorsLastWeekWidget extends Widget
{
    public function html(): View
    {
        $data = $this->getStatsPastWeek();
        $title = $this->config('title', __('Errors last week'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }

    private function getStatsPastWeek(): array
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
}
