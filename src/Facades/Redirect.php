<?php

namespace Rias\StatamicRedirect\Facades;

use Illuminate\Support\Facades\Facade;
use Rias\StatamicRedirect\Contracts\RedirectRepository;

class Redirect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RedirectRepository::class;
    }
}
