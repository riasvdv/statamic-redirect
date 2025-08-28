<?php

namespace Rias\StatamicRedirect\Http\Controllers\Errors;

use Rias\StatamicRedirect\Data\Error;

class ActionController extends \Statamic\Http\Controllers\CP\ActionController
{
    protected static $key = 'errors';

    protected function getSelectedItems($items, $context)
    {
        return $items->map(function ($item) {
            return Error::find($item);
        });
    }
}
