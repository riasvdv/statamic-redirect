<?php

namespace Rias\StatamicRedirect\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class Error extends DataTransferObject
{
    /** @var string|int|null */
    public $id;

    /** @var string */
    public $url;

    /** @var int */
    public $date;

    /** @var bool */
    public $handled = false;
}
