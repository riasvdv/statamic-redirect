<?php

namespace Rias\StatamicRedirect\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class ErrorCollection extends DataTransferObjectCollection
{
    public function current(): Error
    {
        return parent::current();
    }
}
