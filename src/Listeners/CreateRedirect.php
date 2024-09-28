<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Events\EntrySaved;

class CreateRedirect
{
    public function handle(EntrySaved $entrySaved)
    {
        if (! config('statamic.redirect.enable', true)) {
            return;
        }

        /** @var \Statamic\Entries\Entry $entry */
        $entry = $entrySaved->entry;

        /*
         * If we have a redirect with a source of the
         * NEW uri we should remove this redirect.
         */
        if (config('statamic.redirect.delete_conflicting_redirects', true) && $entry->uri() && $entry->published() && $existingRedirect = Redirect::findByUrl($entry->locale(), $entry->uri())) {
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
            ->locale($entry->locale())
            ->source($oldUri)
            ->destination($entry->uri())
            ->matchType(MatchTypeEnum::EXACT)
            ->save();
    }
}
