<?php

namespace Rias\StatamicRedirect\Widgets;

use Illuminate\Contracts\View\View;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Http\Controllers\DashboardController;
use Statamic\Widgets\Widget;

final class ErrorsLastDayWidget extends Widget
{
    public function html(): View
    {
        $data = $this->getStatsPastDay();

        $title = $this->config('title', __('Errors last day'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }

    private function getStatsPastDay(): array
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
