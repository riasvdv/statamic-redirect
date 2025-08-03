<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Entries\Entry;
use Statamic\Events\CollectionTreeSaved;
use Statamic\Events\EntrySaved;
use Statamic\Facades\Blink;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Support\Arr;

class CreateRedirect
{
    public function handle(EntrySaved|CollectionTreeSaved $event): void
    {
        if (! config('statamic.redirect.enable', true)) {
            return;
        }

        match(true) {
            $event instanceof EntrySaved => $this->createRedirect($event->entry),
            $event instanceof CollectionTreeSaved => $this->createRedirect($this->treeToEntries($event->tree->tree())),
        };
    }

    protected function treeToEntries(array $tree): array
    {
        $ids = [];

        foreach ($tree as $item) {
            $ids = array_merge($ids, $this->gatherEntryIds($item));
        }

        foreach($ids as $id) {
            Blink::forget('eloquent-entry-'.$id);
        }

        return EntryFacade::query()->whereIn('id', $ids)->get()->all();
    }

    protected function gatherEntryIds(array $item): array
    {
        $ids = [];

        if (isset($item['entry'])) {
            $ids[] = $item['entry'];
        }

        if (! isset($item['children'])) {
            return $ids;
        }

        foreach ($item['children'] as $child) {
            $ids = array_merge($ids, $this->gatherEntryIds($child));
        }

        return $ids;
    }

    protected function createRedirect(Entry|array $entries): void
    {
        $entries = Arr::wrap($entries);

        foreach ($entries as $entry) {
            if (! $entry->uri()) {
                continue;
            }

            /*
             * If we have a redirect with a source of the
             * NEW uri we should remove this redirect.
             */
            if (config('statamic.redirect.delete_conflicting_redirects', true) && $entry->published() && $existingRedirect = Redirect::findByUrl($entry->site(), $entry->uri())) {
                $existingRedirect->delete();
            }

            if (! config('statamic.redirect.create_entry_redirects', true)) {
                continue;
            }

            if (! $oldUri = Cache::pull("redirect-entry-uri-before:{$entry->id()}")) {
                continue;
            }

            if ($entry->uri() === $oldUri) {
                continue;
            }

            Redirect::make()
                ->site($entry->site())
                ->source($oldUri)
                ->destination($entry->uri())
                ->matchType(MatchTypeEnum::EXACT)
                ->save();
        }
    }
}
