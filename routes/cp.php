<?php

use Rias\StatamicRedirect\Controllers\Api\RedirectController as ApiRedirectController;
use Rias\StatamicRedirect\Controllers\DashboardController;
use Rias\StatamicRedirect\Controllers\Api\ErrorController as ApiErrorController;
use Rias\StatamicRedirect\Controllers\ErrorController;
use Rias\StatamicRedirect\Controllers\ExportController;
use Rias\StatamicRedirect\Controllers\ImportRedirectsController;
use Rias\StatamicRedirect\Controllers\RedirectController;

Route::get('redirect/dashboard', '\\'. DashboardController::class)->name('redirect.index');
Route::get('redirect/export/{type}', '\\'. ExportController::class)->name('redirect.export');

Route::get('redirect/api/errors', ['\\' . ApiErrorController::class, 'index'])->name('redirect.api.errors.index');
Route::get('redirect/api/redirects', ['\\' . ApiRedirectController::class, 'index'])->name('redirect.api.redirects.index');

Route::prefix('redirect/errors')->group(function () {
    Route::get('clear', ['\\' . ErrorController::class, 'clearAll'])->name('redirect.api.errors.clear');
    Route::get('/{error}', ['\\' . ErrorController::class, 'show'])->name('redirect.errors.show');
    Route::get('/{error}/delete', ['\\' . ErrorController::class, 'delete'])->name('redirect.errors.delete');
});

Route::prefix('redirect/redirects')->group(function () {
    Route::get('/', ['\\' . RedirectController::class, 'index'])->name('redirect.redirects.index');
    Route::get('/import', ['\\' . ImportRedirectsController::class, 'index'])->name('redirect.redirects.import');
    Route::post('/import', ['\\' . ImportRedirectsController::class, 'store'])->name('redirect.redirects.handleImport');
    Route::get('/create', ['\\' . RedirectController::class, 'create'])->name('redirect.redirects.create');
    Route::get('/{id}', ['\\' . RedirectController::class, 'edit'])->name('redirect.redirects.edit');
    Route::post('/{id}', ['\\' . RedirectController::class, 'update'])->name('redirect.redirects.update');
    Route::delete('/{id}', ['\\' . RedirectController::class, 'destroy'])->name('redirect.redirects.delete');
    Route::post('/', ['\\' . RedirectController::class, 'store'])->name('redirect.redirects.store');
});
