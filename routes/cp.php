<?php

use Rias\StatamicRedirect\Controllers\Api\RedirectController as ApiRedirectController;
use Rias\StatamicRedirect\Controllers\DashboardController;
use Rias\StatamicRedirect\Controllers\Api\ErrorController;
use Rias\StatamicRedirect\Controllers\RedirectController;

Route::get('redirect/dashboard', '\\'. DashboardController::class)->name('redirect.index');

Route::get('redirect/api/errors', ['\\' . ErrorController::class, 'index'])->name('redirect.api.errors.index');
Route::get('redirect/api/redirects', ['\\' . ApiRedirectController::class, 'index'])->name('redirect.api.redirects.index');

Route::prefix('redirect/redirects')->group(function () {
    Route::get('/', ['\\' . RedirectController::class, 'index'])->name('redirect.redirects.index');
    Route::get('/create', ['\\' . RedirectController::class, 'create'])->name('redirect.redirects.create');
    Route::get('/{id}', ['\\' . RedirectController::class, 'edit'])->name('redirect.redirects.edit');
    Route::post('/{id}', ['\\' . RedirectController::class, 'update'])->name('redirect.redirects.update');
    Route::delete('/{id}', ['\\' . RedirectController::class, 'destroy'])->name('redirect.redirects.delete');
    Route::post('/', ['\\' . RedirectController::class, 'store'])->name('redirect.redirects.store');
});
