<?php

namespace Rias\StatamicRedirect\Http\Filters;

class Site extends \Statamic\Query\Scopes\Filters\Site
{
    public function visibleTo($key)
    {
        return $key === 'redirects' && \Statamic\Facades\Site::hasMultiple();
    }
}
