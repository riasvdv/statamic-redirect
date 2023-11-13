<?php

namespace Rias\StatamicRedirect\Facades;

use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Illuminate\Support\Facades\Facade;

class Redirect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RedirectRepository::class;
    }
}
