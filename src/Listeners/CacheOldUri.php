<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Statamic\Events\CollectionTreeSaving;
use Statamic\Events\EntrySaving;
use Statamic\Facades\Entry;

class CacheOldUri
{
    public function handle(EntrySaving|CollectionTreeSaving $event): void
    {
        if (! config('statamic.redirect.enable', true)) {
            return;
        }

        if ($event instanceof EntrySaving) {
            if (! $event->entry->id()) {
                return;
            }

            $this->cacheEntryUri($event->entry->id());

            return;
        }

        /** @var \Statamic\Structures\CollectionTreeDiff $diff */
        $diff = $event->tree->diff();

        foreach ($diff->affected() as $entry) {
            $this->cacheEntryUri($entry);
        }
    }

    protected function cacheEntryUri(string $entryId): void
    {
        $entry = Entry::find($entryId);

        if (! $entry || ! $uri = $entry->uri()) {
            return;
        }

        if (! $entry->published()) {
            return;
        }

        Cache::put("redirect-entry-uri-before:{$entry->id()}", $uri, now()->addMinute());
    }
}
