<?php

use Illuminate\Support\Facades\Route;
use Rias\StatamicRedirect\Http\Controllers\Api\RedirectsController;

Route::get('redirects', [RedirectsController::class, 'index'])->name('statamic.api.redirects.index');
Route::get('redirects/{redirect}', [RedirectsController::class, 'show'])->name('statamic.api.redirects.show');
