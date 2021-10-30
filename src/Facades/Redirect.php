<?php

namespace Rias\StatamicRedirect\Facades;

use Illuminate\Support\Facades\Facade;
use Rias\StatamicRedirect\Contracts\RedirectRepository;

/**
 * @method static \Rias\StatamicRedirect\Data\Redirect make()
 * @method static \Statamic\Data\DataCollection all()
 * @method static null|\Rias\StatamicRedirect\Data\Redirect find($id)
 * @method static null|\Rias\StatamicRedirect\Data\Redirect findByUrl(string $url)
 * @method static void save(\Rias\StatamicRedirect\Data\Redirect $error);
 * @method static void delete(\Rias\StatamicRedirect\Data\Redirect $error);
 * @method static \Rias\StatamicRedirect\Stache\Redirects\RedirectQueryBuilder query()
 *
 * @see \Rias\StatamicRedirect\Contracts\RedirectRepository
 */
class Redirect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return config('statamic.redirect.redirect_repository');
    }
}
