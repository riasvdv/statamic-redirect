<?php

use Rias\StatamicRedirect\Controllers\ErrorController;
use Rias\StatamicRedirect\Controllers\DashboardController;
use Rias\StatamicRedirect\Controllers\RedirectController;

Route::get('redirect/dashboard', '\\'. DashboardController::class)->name('redirect.index');
Route::get('redirect/errors', ['\\' . ErrorController::class, 'index'])->name('redirect.errors.index');
Route::get('redirect/errors/{redirect_error}', ['\\' . ErrorController::class, 'show'])->name('redirect.errors.show');

Route::get('redirect/redirects', ['\\' . RedirectController::class, 'index'])->name('redirect.redirects.index');
Route::get('redirect/redirects/create', ['\\' . RedirectController::class, 'create'])->name('redirect.redirects.create');
Route::get('redirect/redirects/{redirect}', ['\\' . RedirectController::class, 'edit'])->name('redirect.redirects.edit');
Route::post('redirect/redirects/{redirect}', ['\\' . RedirectController::class, 'update'])->name('redirect.redirects.update');
Route::delete('redirect/redirects/{redirect}', ['\\' . RedirectController::class, 'destroy'])->name('redirect.redirects.delete');
Route::post('redirect/redirects', ['\\' . RedirectController::class, 'store'])->name('redirect.redirects.store');
