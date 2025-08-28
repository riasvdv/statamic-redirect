<?php

namespace Rias\StatamicRedirect\Widgets;

use Rias\StatamicRedirect\Http\Controllers\DashboardController;
use Statamic\Widgets\Widget;

class ErrorsLastDayWidget extends Widget
{
    public function html()
    {
        $data = app(DashboardController::class)->getStatsPastDay();

        $title = $this->config('title', __('Errors last day'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }
}
