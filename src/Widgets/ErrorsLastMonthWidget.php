<?php

namespace Rias\StatamicRedirect\Widgets;

use Rias\StatamicRedirect\Controllers\DashboardController;
use Statamic\Widgets\Widget;

class ErrorsLastMonthWidget extends Widget
{
    public function html()
    {
        $data = app(DashboardController::class)->getStatsPastMonth();
        $title = $this->config('title', __('Errors last month'));

        return view('redirect::widgets.errors_chart', compact('data', 'title'));
    }
}
