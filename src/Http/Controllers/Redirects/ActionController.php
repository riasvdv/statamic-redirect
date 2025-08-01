<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Rias\StatamicRedirect\Facades\Redirect;

class ActionController extends \Statamic\Http\Controllers\CP\ActionController
{
    protected static $key = 'redirects';

    protected function getSelectedItems($items, $context)
    {
        return $items->map(function ($item) {
            return Redirect::find($item);
        });
    }
}
