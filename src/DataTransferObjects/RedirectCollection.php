<?php

namespace Rias\StatamicRedirect\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class RedirectCollection extends DataTransferObjectCollection
{
    public function current(): Redirect
    {
        return parent::current();
    }
}
