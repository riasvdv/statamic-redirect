<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Facades\Error;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Stache\Errors\ErrorRepository;
use Rias\StatamicRedirect\Repositories\FileRedirectRepository;
use Rias\StatamicRedirect\Tests\TestCase;

class HandleNotFoundTest extends TestCase
{
    /** @var HandleNotFound */
    private $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = app(HandleNotFound::class);
    }

    /** @test * */
    public function it_does_nothing_if_the_response_is_not_a_404()
    {
        $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertEquals(0, Error::query()->count());
    }

    /** @test * */
    public function it_creates_an_error_when_the_response_is_404()
    {
        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals('/abc', Error::query()->first()->url());
        $this->assertEquals(404, $response->status());
    }

    /** @test * */
    public function it_redirects_and_sets_handled_if_a_redirect_is_found()
    {
        \Rias\StatamicRedirect\Facades\Redirect::make()
            ->source('/abc')
            ->destination('/def')
            ->save();

        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertEquals(1, Error::query()->count());
        $this->assertEquals('/abc', Error::query()->first()->url());
        $this->assertEquals(true, Error::query()->first()->handled());

        $this->assertTrue($response->isRedirect(url('/def')));
    }
}
