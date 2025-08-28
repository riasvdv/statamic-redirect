<?php

namespace Rias\StatamicRedirect\Widgets;

use Rias\StatamicRedirect\Data\Error;
use Statamic\Widgets\Widget;

class TopErrorsWidget extends Widget
{
    public function html()
    {
        $query = Error::query();
        $query->orderBy('hitsCount', 'desc');
        $query->limit($this->config('limit', 5));

        return view('redirect::widgets.errors', [
            'title' => $this->config('title', __('Top 404 Errors')),
            'errors' => $query->get(),
        ]);
    }
}
