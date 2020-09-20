<?php

use Rias\StatamicRedirect\Controllers\DashboardController;
use Rias\StatamicRedirect\Controllers\RedirectController;

Route::get('redirect/dashboard', '\\'. DashboardController::class)->name('redirect.index');

Route::get('redirect/redirects', ['\\' . RedirectController::class, 'index'])->name('redirect.redirects.index');
Route::get('redirect/redirects/create', ['\\' . RedirectController::class, 'create'])->name('redirect.redirects.create');
Route::get('redirect/redirects/{id}', ['\\' . RedirectController::class, 'edit'])->name('redirect.redirects.edit');
Route::post('redirect/redirects/{id}', ['\\' . RedirectController::class, 'update'])->name('redirect.redirects.update');
Route::delete('redirect/redirects/{id}', ['\\' . RedirectController::class, 'destroy'])->name('redirect.redirects.delete');
Route::post('redirect/redirects', ['\\' . RedirectController::class, 'store'])->name('redirect.redirects.store');
