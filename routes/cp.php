<?php

use Rias\StatamicRedirect\Http\Controllers\Api\ErrorController as ApiErrorController;
use Rias\StatamicRedirect\Http\Controllers\Api\RedirectController as ApiRedirectController;
use Rias\StatamicRedirect\Http\Controllers\DashboardController;
use Rias\StatamicRedirect\Http\Controllers\ErrorController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ExportController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ImportRedirectsController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\RedirectController;

Route::get('redirect/dashboard', '\\'. DashboardController::class)->name('redirect.index');
Route::get('redirect/export/{type}', '\\'. ExportController::class)->name('redirect.export');

Route::get('redirect/api/errors', ['\\' . ApiErrorController::class, 'index'])->name('redirect.api.errors.index');
Route::get('redirect/api/redirects', ['\\' . ApiRedirectController::class, 'index'])->name('redirect.api.redirects.index');
Route::post('redirect/api/redirects/reorder', ['\\' . ApiRedirectController::class, 'reorder'])->name('redirect.api.redirects.reorder');

Route::prefix('redirect/errors')->group(function () {
    Route::post('clear', ['\\' . ErrorController::class, 'clearAll'])->name('redirect.errors.clear');

    Route::post('actions/run', ['\\' . \Rias\StatamicRedirect\Http\Controllers\Errors\ActionController::class, 'run'])->name('redirect.errors.actions.run');
    Route::post('actions/run/list', ['\\' . \Rias\StatamicRedirect\Http\Controllers\Errors\ActionController::class, 'bulkActions'])->name('redirect.errors.actions.bulk');

    Route::get('/{error}', ['\\' . ErrorController::class, 'show'])->name('redirect.errors.show');
    Route::get('/{error}/delete', ['\\' . ErrorController::class, 'delete'])->name('redirect.errors.delete');
});

Route::prefix('redirect/redirects')->group(function () {
    Route::get('/', ['\\' . RedirectController::class, 'index'])->name('redirect.redirects.index');

    Route::get('/import', ['\\' . ImportRedirectsController::class, 'index'])->name('redirect.redirects.import');
    Route::post('/import', ['\\' . ImportRedirectsController::class, 'store'])->name('redirect.redirects.handleImport');

    Route::get('/create', ['\\' . RedirectController::class, 'create'])->name('redirect.redirects.create');
    Route::post('/', ['\\' . RedirectController::class, 'store'])->name('redirect.redirects.store');

    Route::post('actions/run', ['\\' . \Rias\StatamicRedirect\Http\Controllers\Redirects\ActionController::class, 'run'])->name('redirect.redirects.actions.run');
    Route::post('actions/run/list', ['\\' . \Rias\StatamicRedirect\Http\Controllers\Redirects\ActionController::class, 'bulkActions'])->name('redirect.redirects.actions.bulk');

    Route::get('/{id}', ['\\' . RedirectController::class, 'edit'])->name('redirect.redirects.edit');
    Route::post('/{id}', ['\\' . RedirectController::class, 'update'])->name('redirect.redirects.update');
    Route::delete('/{id}', ['\\' . RedirectController::class, 'destroy'])->name('redirect.redirects.delete');
});
