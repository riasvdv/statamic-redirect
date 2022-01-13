<?php

namespace Rias\StatamicRedirect\Tests\Feature\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Rias\StatamicRedirect\Controllers\ImportRedirectsController;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;

class ImportRedirectsControllerTest extends TestCase
{
    /**
     * @test
     * @dataProvider repositories
     */
    public function it_can_import_redirects($errorRepository, $redirectRepository)
    {
        config()->set('statamic.redirect.error_repository', $errorRepository);
        config()->set('statamic.redirect.redirect_repository', $redirectRepository);

        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl('/foo'), function (\Rias\StatamicRedirect\Data\Redirect $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }
}
