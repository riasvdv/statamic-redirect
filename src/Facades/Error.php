<?php

namespace Rias\StatamicRedirect\Facades;

use Illuminate\Support\Facades\Facade;
use Rias\StatamicRedirect\Contracts\ErrorRepository;

/**
 * @method static \Rias\StatamicRedirect\Data\Error make()
 * @method static \Statamic\Data\DataCollection all()
 * @method static null|\Rias\StatamicRedirect\Data\Error find($id)
 * @method static null|\Rias\StatamicRedirect\Data\Error findByUrl(string $url)
 * @method static void save(\Rias\StatamicRedirect\Data\Error $error);
 * @method static void delete(\Rias\StatamicRedirect\Data\Error $error);
 * @method static \Rias\StatamicRedirect\Stache\Errors\ErrorQueryBuilder query()
 *
 * @see \Rias\StatamicRedirect\Contracts\ErrorRepository
 */
class Error extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ErrorRepository::class;
    }
}
