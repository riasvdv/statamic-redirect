<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\Entry;
use Statamic\Support\Str;
use Statamic\UpdateScripts\UpdateScript;

class PreserveEntryDestinations extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.2.0');
    }

    public function update()
    {
        $updated = 0;
        $skipped = 0;

        Redirect::all()
            ->filter(fn ($redirect) => $redirect->destination_type() === 'entry'
                && $redirect->destination_entry()
                && ! Str::startsWith($redirect->rawDestination() ?? '', 'entry::'))
            ->each(function ($redirect) use (&$updated, &$skipped) {
                $entry = Entry::find($redirect->destination_entry());

                if (! $entry) {
                    $this->console()->warning(sprintf(
                        'Skipped redirect [%s]: entry [%s] does not exist.',
                        $redirect->id(),
                        $redirect->destination_entry(),
                    ));
                    $skipped++;

                    return;
                }

                $localizedEntry = $entry->in($redirect->site()) ?? $entry;

                $redirect->destination("entry::{$localizedEntry->id()}")->save();
                $updated++;
            });

        Cache::forget('statamic.redirect.redirects');

        $this->console()->info("Updated {$updated} entry redirect(s); skipped {$skipped}.");
    }
}
