<?php

namespace Rias\StatamicRedirect\Tests\Feature\Controllers;

use Illuminate\Http\UploadedFile;
use Rias\StatamicRedirect\Controllers\ImportRedirectsController;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;

class ImportRedirectsControllerTest extends TestCase
{
    /** @test * */
    public function it_can_import_redirects()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully');

        $this->assertEquals(1, Redirect::query()->count());
        $this->assertEquals('/foo', Redirect::query()->first()->source());
        $this->assertEquals('/bar', Redirect::query()->first()->destination());
        $this->assertEquals('302', Redirect::query()->first()->type());
        $this->assertEquals('exact', Redirect::query()->first()->matchType());
    }
}
