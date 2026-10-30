<?php

namespace Rias\StatamicRedirect\Tests\Feature;

use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;
use Statamic\Facades\Stache;

class RedirectTest extends TestCase
{
    #[Test]
    public function it_dispatches_an_event_when_saving(): void
    {
        Event::fake();

        Redirect::make()->save();

        Event::assertDispatched(RedirectSaved::class);
    }

    #[Test]
    public function it_keeps_the_description_when_a_stache_redirect_is_loaded_and_saved_again(): void
    {
        Redirect::make()
            ->id('described')
            ->source('/old')
            ->destination('/new')
            ->description('Keep me')
            ->save();

        Stache::clear();
        Redirect::find('described')->save();
        Stache::clear();

        $this->assertSame('Keep me', Redirect::find('described')->description());
    }
}
