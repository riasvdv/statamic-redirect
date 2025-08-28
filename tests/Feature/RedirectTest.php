<?php

use Illuminate\Support\Facades\Event;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Facades\Redirect;

it('dispatches an event when saving', function () {
    Event::fake();

    Redirect::make()->save();

    Event::assertDispatched(RedirectSaved::class);
});
