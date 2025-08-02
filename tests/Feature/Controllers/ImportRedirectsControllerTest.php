<?php

namespace Rias\StatamicRedirect\Tests\Feature\Controllers;

use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ImportRedirectsController;
use Rias\StatamicRedirect\Tests\TestCase;

class ImportRedirectsControllerTest extends TestCase
{
    #[Test]
    public function it_can_import_redirects()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    #[Test]
    public function it_can_set_the_site()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type,site\n/foo,/bar,302,exact,en");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl('en', '/foo'), function (RedirectContract $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    #[Test]
    public function it_can_import_redirects_with_a_txt_file()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.txt', "source,destination,type,match_type\n/foo,/bar,302,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    #[Test]
    public function it_will_ignore_invalid_redirects()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact\n,/bar,302,exact\n/foo,,302,exact\n/foo,/bar,,exact\n/foo,/bar,302,");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully. 4 rows skipped due to invalid data. You can find more info in the Laravel log.');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('302', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }

    #[Test]
    public function it_will_update_redirects_with_duplicate_source()
    {
        $this->asAdmin();

        $file = UploadedFile::fake()->createWithContent('redirects.txt', "source,destination,type,match_type\n/foo,/bar,302,exact\n/foo,/bar,301,exact");

        $this->assertEquals(0, Redirect::query()->count());

        $this->post(action([ImportRedirectsController::class, 'store']), [
            'file' => $file,
            'delimiter' => ',',
        ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

        $this->assertEquals(1, Redirect::query()->count());
        tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
            $this->assertEquals('/bar', $redirect->destination());
            $this->assertEquals('301', $redirect->type());
            $this->assertEquals('exact', $redirect->matchType());
        });
    }
}
