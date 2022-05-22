<?php

namespace Rias\StatamicRedirect\Tests\Feature\Controllers;

use Illuminate\Http\UploadedFile;
use Rias\StatamicRedirect\Controllers\ImportRedirectsController;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;

class ImportRedirectsControllerTest extends TestCase
{
    /**
     * @test
     */
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
        tap(Redirect::findByUrl('/foo'), function (Redirect $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    /**
     * @test
     */
    public function it_can_import_redirects_with_a_txt_file()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.txt', "source,destination,type,match_type\n/foo,/bar,302,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl('/foo'), function (Redirect $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    /**
     * @test
     */
    public function it_will_ignore_invalid_redirects()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact\n,/bar,302,exact\n/foo,,302,exact\n/foo,/bar,,exact\n/foo,/bar,302,");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', "Redirects imported successfully. 4 rows skipped due to invalid data.");

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl('/foo'), function (Redirect $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }
}
