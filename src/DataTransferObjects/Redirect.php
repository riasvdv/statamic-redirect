<?php

namespace Rias\StatamicRedirect\DataTransferObjects;

use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Spatie\DataTransferObject\DataTransferObject;

class Redirect extends DataTransferObject
{
    /** @var int|null */
    public $id;

    /** @var bool */
    public $enabled = true;

    /** @var string */
    public $source;

    /** @var string */
    public $destination;

    /** @var string */
    public $type = '301';

    /** @var string */
    public $match_type = MatchTypeEnum::EXACT;
}
