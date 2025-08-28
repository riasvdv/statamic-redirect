<?php

use Illuminate\Http\UploadedFile;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ImportRedirectsController;

it('can import redirects', function () {
    $this->asAdmin();

    $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact");

    expect(Redirect::query()->count())->toEqual(0);

    $this->post(action([ImportRedirectsController::class, 'store']), [
        'file' => $file,
        'delimiter' => ',',
    ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

    expect(Redirect::query()->count())->toEqual(1);
    tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
        expect($redirect->destination())->toEqual('/bar');
        expect($redirect->type())->toEqual('302');
        expect($redirect->matchType())->toEqual('exact');
    });
});

it('can set the site', function () {
    $this->asAdmin();

    $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type,site\n/foo,/bar,302,exact,en");

    expect(Redirect::query()->count())->toEqual(0);

    $this->post(action([ImportRedirectsController::class, 'store']), [
        'file' => $file,
        'delimiter' => ',',
    ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

    expect(Redirect::query()->count())->toEqual(1);
    tap(Redirect::findByUrl('en', '/foo'), function (RedirectContract $redirect) {
        expect($redirect->destination())->toEqual('/bar');
        expect($redirect->type())->toEqual('302');
        expect($redirect->matchType())->toEqual('exact');
    });
});

it('can import redirects with a txt file', function () {
    $this->asAdmin();

    $file = UploadedFile::fake()->createWithContent('redirects.txt', "source,destination,type,match_type\n/foo,/bar,302,exact");

    expect(Redirect::query()->count())->toEqual(0);

    $this->post(action([ImportRedirectsController::class, 'store']), [
        'file' => $file,
        'delimiter' => ',',
    ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

    expect(Redirect::query()->count())->toEqual(1);
    tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
        expect($redirect->destination())->toEqual('/bar');
        expect($redirect->type())->toEqual('302');
        expect($redirect->matchType())->toEqual('exact');
    });
});

it('will ignore invalid redirects', function () {
    $this->asAdmin();

    $file = UploadedFile::fake()->createWithContent('redirects.csv', "source,destination,type,match_type\n/foo,/bar,302,exact\n,/bar,302,exact\n/foo,,302,exact\n/foo,/bar,,exact\n/foo,/bar,302,");

    expect(Redirect::query()->count())->toEqual(0);

    $this->post(action([ImportRedirectsController::class, 'store']), [
        'file' => $file,
        'delimiter' => ',',
    ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully. 4 rows skipped due to invalid data. You can find more info in the Laravel log.');

    expect(Redirect::query()->count())->toEqual(1);
    tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
        expect($redirect->destination())->toEqual('/bar');
        expect($redirect->type())->toEqual('302');
        expect($redirect->matchType())->toEqual('exact');
    });
});

it('will update redirects with duplicate source', function () {
    $this->asAdmin();

    $file = UploadedFile::fake()->createWithContent('redirects.txt', "source,destination,type,match_type\n/foo,/bar,302,exact\n/foo,/bar,301,exact");

    expect(Redirect::query()->count())->toEqual(0);

    $this->post(action([ImportRedirectsController::class, 'store']), [
        'file' => $file,
        'delimiter' => ',',
    ])->assertRedirect()->assertSessionHas('success', 'Redirects imported successfully.');

    expect(Redirect::query()->count())->toEqual(1);
    tap(Redirect::findByUrl(\Statamic\Facades\Site::default()->handle(), '/foo'), function (RedirectContract $redirect) {
        expect($redirect->destination())->toEqual('/bar');
        expect($redirect->type())->toEqual('301');
        expect($redirect->matchType())->toEqual('exact');
    });
});
