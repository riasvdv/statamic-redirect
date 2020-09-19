<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Statamic\Events\EntrySaving;
use Statamic\Facades\Entry;

class CacheOldUri
{
    public function handle(EntrySaving $entrySaving)
    {
        $entry = Entry::find($entrySaving->entry->id());

        if (! $entry || ! $uri = $entry->uri()) {
            return;
        }

        Cache::put('redirect-entry-uri-before', $uri);
    }
}
