<?php

namespace Rias\StatamicRedirect\Listeners;

use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\Repositories\RedirectRepository;
use Statamic\Events\EntrySaved;

class CreateRedirect
{
    /** @var \Rias\StatamicRedirect\Repositories\RedirectRepository */
    private $redirectRepository;

    public function __construct(RedirectRepository $redirectRepository)
    {
        $this->redirectRepository = $redirectRepository;
    }

    public function handle(EntrySaved $entrySaved)
    {
        /** @var \Statamic\Entries\Entry $entry */
        $entry = $entrySaved->entry;

        /*
         * If we have a redirect with a source of the
         * NEW uri we should remove this redirect.
         */
        if ($existingRedirect = $this->redirectRepository->findForUrl($entry->uri())) {
            $this->redirectRepository->delete($existingRedirect);
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

        $this->redirectRepository->save(new Redirect([
            'source' => $oldUri,
            'destination' => $entry->uri(),
        ]));
    }
}
