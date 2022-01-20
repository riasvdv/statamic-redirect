<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Data\Redirect;
use Statamic\Events\EntrySaved;

class CreateRedirect
{
    public function handle(EntrySaved $entrySaved)
    {
        /** @var \Statamic\Entries\Entry $entry */
        $entry = $entrySaved->entry;

        /*
         * If we have a redirect with a source of the
         * NEW uri we should remove this redirect.
         */
        if ($entry->uri() && $existingRedirect = Redirect::findByUrl($entry->uri())) {
            $existingRedirect->delete();
        }

        if (! $oldUri = Cache::pull('redirect-entry-uri-before')) {
            return;
        }

        if ($entry->uri() === $oldUri) {
            return;
        }

        if (! config('statamic.redirect.create_entry_redirects', true)) {
            return;
        }

        Redirect::make()
            ->source($oldUri)
            ->destination($entry->uri())
            ->save();
    }
}
