<?php

namespace Rias\StatamicRedirect\Events;

use Rias\StatamicRedirect\Data\Redirect;
use Statamic\Contracts\Git\ProvidesCommitMessage;
use Statamic\Events\Event;

class RedirectSaved extends Event implements ProvidesCommitMessage
{
    public Redirect $item;

    public function __construct(Redirect $item)
    {
        $this->item = $item;
    }

    public function commitMessage()
    {
        return __('Redirect saved');
    }
}
