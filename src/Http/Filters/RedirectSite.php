<?php

namespace Rias\StatamicRedirect\Http\Filters;

class RedirectSite extends \Statamic\Query\Scopes\Filters\Site
{
    public function visibleTo($key)
    {
        return $key === 'redirects' && \Statamic\Facades\Site::hasMultiple();
    }
}
