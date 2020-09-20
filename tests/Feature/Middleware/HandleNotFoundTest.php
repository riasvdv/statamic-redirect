<?php

namespace Rias\StatamicRedirect\Tests\Feature\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Repositories\FileErrorRepository;
use Rias\StatamicRedirect\Repositories\FileRedirectRepository;
use Rias\StatamicRedirect\Tests\TestCase;

class HandleNotFoundTest extends TestCase
{
    /** @var HandleNotFound */
    private $middleware;

    /** @var FileErrorRepository */
    private $errorRepository;

    /** @var FileRedirectRepository */
    private $redirectRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = app(HandleNotFound::class);
        $this->errorRepository = app(FileErrorRepository::class);
        $this->redirectRepository = app(FileRedirectRepository::class);
    }

    /** @test * */
    public function it_does_nothing_if_the_response_is_not_a_404()
    {
        $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 200));
        });

        $this->assertCount(0, $this->errorRepository->all());
    }

    /** @test * */
    public function it_creates_an_error_when_the_response_is_404()
    {
        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertCount(1, $this->errorRepository->all());
        $this->assertEquals('/abc', $this->errorRepository->all()[0]->url);
        $this->assertEquals(404, $response->status());
    }

    /** @test * */
    public function it_redirects_and_sets_handled_if_a_redirect_is_found()
    {
        $this->redirectRepository->save(new Redirect([
            'source' => '/abc',
            'destination' => '/def',
        ]));

        $response = $this->middleware->handle(Request::create('/abc'), function () {
            return (new Response('', 404));
        });

        $this->assertCount(1, $this->errorRepository->all());
        $this->assertEquals('/abc', $this->errorRepository->all()[0]->url);
        $this->assertEquals(true, $this->errorRepository->all()[0]->handled);

        $this->assertTrue($response->isRedirect(url('/def')));
    }
}
