<?php

namespace Rias\StatamicRedirect\Actions;

use Rias\StatamicRedirect\Contracts\Redirect;
use Rias\StatamicRedirect\Data\Error;

class Delete extends \Statamic\Actions\Delete
{
    public function visibleTo($item)
    {
        if ($item instanceof Redirect) {
            return true;
        }

        if ($item instanceof Error) {
            return true;
        }

        return parent::visibleTo($item);
    }

    public function redirect($items, $values)
    {
        $item = $items->first();

        if ($item instanceof Redirect) {
            return cp_route('redirect.redirects.index');
        }

        if ($item instanceof Error) {
            return cp_route('redirect.errors.index');
        }

        return parent::redirect($items, $values);
    }
}
