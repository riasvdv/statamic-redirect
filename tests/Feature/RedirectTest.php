<?php

namespace Rias\StatamicRedirect\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Tests\TestCase;

class RedirectTest extends TestCase
{
    /** @test * */
    public function it_dispatches_an_event_when_saving(): void
    {
        Event::fake();

        Redirect::make()->save();

        Event::assertDispatched(RedirectSaved::class);
    }
}
