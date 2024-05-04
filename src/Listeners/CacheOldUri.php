<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Statamic\Events\EntrySaving;
use Statamic\Facades\Blink;
use Statamic\Facades\Entry;

class CacheOldUri
{
    public function handle(EntrySaving $entrySaving)
    {
        if (! config('statamic.redirect.enable', true)) {
            return;
        }

        $id = $entrySaving->entry->id();

        Blink::forget('eloquent-entry-'.$id);
        $entry = Entry::find($id);

        if (! $entry || ! $uri = $entry->uri()) {
            return;
        }

        if (! $entry->published()) {
            return;
        }

        Cache::put('redirect-entry-uri-before', $uri);
    }
}
