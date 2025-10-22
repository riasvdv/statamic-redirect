<?php

namespace Rias\StatamicRedirect\Widgets;

use Illuminate\Contracts\View\View;
use Rias\StatamicRedirect\Data\Error;
use Statamic\Widgets\Widget;

final class TopErrorsWidget extends Widget
{
    public function setConfig($config): self
    {
        parent::setConfig($config);

        return $this;
    }

    public function html(): View
    {
        return view('redirect::widgets.errors', [
            'title' => $this->config('title', __('Top 404 Errors')),
            'errors' => Error::query()
                ->orderBy('hitsCount', 'desc')
                ->limit($this->config('limit', 5))
                ->get(),
        ]);
    }
}
