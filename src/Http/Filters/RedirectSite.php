<?php

namespace Rias\StatamicRedirect\Http\Filters;

use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Query\Scopes\Filters\Site;

class RedirectSite extends Site
{
    public function visibleTo($key): bool
    {
        return $key === 'redirects' && \Statamic\Facades\Site::hasMultiple();
    }

    protected function availableSites()
    {
        if (! \Statamic\Facades\Site::hasMultiple()) {
            return collect();
        }

        $configuredSites = collect();
        $redirects = Redirect::all();
        foreach ($redirects as $redirect) {
            if (! $configuredSites->contains($redirect->site())) {
                $configuredSites->add($redirect->site());
            }
        }

        return \Statamic\Facades\Site::authorized()->filter(function ($site) use ($configuredSites) {
            return $configuredSites->contains($site->handle());
        });
    }
}
