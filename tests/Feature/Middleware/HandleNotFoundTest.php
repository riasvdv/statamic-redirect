<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rias\StatamicRedirect\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Models\Error;
use Rias\StatamicRedirect\Models\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;

class HandleNotFoundTest extends TestCase
{
    /** @test * */
    public function it_does_nothing_if_the_response_is_not_a_404()
    {
        $middleware = new HandleNotFound();

        $middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertEquals(0, Error::all()->count());
    }

    /** @test * */
    public function it_creates_an_error_when_the_response_is_404()
    {
        $middleware = new HandleNotFound();

        $response = $middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::all()->count());
        $this->assertEquals('abc', Error::all()->first()->url);
        $this->assertEquals(404, $response->status());
    }

    /** @test * */
    public function it_redirects_and_sets_handled_if_a_redirect_is_found()
    {
        $middleware = new HandleNotFound();
        Redirect::create([
            'source' => '/abc',
            'destination' => '/def',
        ]);

        $response = $middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::all()->count());
        $this->assertEquals('abc', Error::all()->first()->url);
        $this->assertEquals(true, Error::all()->first()->handled);

        $this->assertTrue($response->isRedirect(url('/def')));
    }
}
