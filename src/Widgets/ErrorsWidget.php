<?php

namespace Rias\StatamicRedirect\Widgets;

use Rias\StatamicRedirect\Data\Error;
use Statamic\Widgets\Widget;

class ErrorsWidget extends Widget
{
    public function html()
    {
        $query = Error::query();
        $query->orderBy('lastSeenAt', 'desc');
        $query->limit($this->config('limit', 5));

        return view('redirect::widgets.errors', [
            'title' => $this->config('title', __('404 Errors')),
            'errors' => $query->get(),
        ]);
    }
}
