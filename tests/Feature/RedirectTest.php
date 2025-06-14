<?php

namespace Rias\StatamicRedirect\Tests\Feature;

use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;

class RedirectTest extends TestCase
{
    #[Test]
    public function it_dispatches_an_event_when_saving(): void
    {
        Event::fake();

        Redirect::make()->save();

        Event::assertDispatched(RedirectSaved::class);
    }
}
