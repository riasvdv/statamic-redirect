<?php

namespace Rias\StatamicRedirect\Widgets;

use Rias\StatamicRedirect\Controllers\DashboardController;
use Statamic\Widgets\Widget;

class ErrorsLastWeekWidget extends Widget
{
    public function html()
    {
        $data = app(DashboardController::class)->getStatsPastWeek();
        $title = $this->config('title', __('Errors last week'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }
}
