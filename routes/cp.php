<?php

use Rias\StatamicRedirect\Http\Controllers\Api\ErrorController as ApiErrorController;
use Rias\StatamicRedirect\Http\Controllers\Api\RedirectController as ApiRedirectController;
use Rias\StatamicRedirect\Http\Controllers\DashboardController;
use Rias\StatamicRedirect\Http\Controllers\Errors\ActionController as ErrorActionController;
use Rias\StatamicRedirect\Http\Controllers\Errors\ErrorController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ActionController as RedirectActionController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ExportController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\ImportRedirectsController;
use Rias\StatamicRedirect\Http\Controllers\Redirects\RedirectController;

Route::prefix('redirect')->name('redirect.')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('index');

    Route::prefix('api')->name('api.')->group(function () {
        Route::get('errors', [ApiErrorController::class, 'index'])->name('errors.index');
        Route::get('redirects', [ApiRedirectController::class, 'index'])->name('redirects.index');
        Route::post('redirects/reorder', [ApiRedirectController::class, 'reorder'])->name('redirects.reorder');
    });

    Route::prefix('errors')->name('errors.')->group(function () {
        Route::get('/', [ErrorController::class, 'index'])->name('index');
        Route::post('clear', [ErrorController::class, 'clearAll'])->name('clear');

        Route::post('actions/run', [ErrorActionController::class, 'run'])->name('actions.run');
        Route::post('actions/run/list', [ErrorActionController::class, 'bulkActions'])->name('actions.bulk');

        Route::get('/{error}', [ErrorController::class, 'show'])->name('show');
        Route::get('/{error}/delete', [ErrorController::class, 'delete'])->name('delete');
    });

    Route::prefix('redirects')->name('redirects.')->group(function () {
        Route::get('/', [RedirectController::class, 'index'])->name('index');

        Route::get('/import', [ImportRedirectsController::class, 'index'])->name('import');
        Route::post('/import', [ImportRedirectsController::class, 'store'])->name('handleImport');

        Route::get('export/{type}', ExportController::class)->name('export');

        Route::get('/create', [RedirectController::class, 'create'])->name('create');
        Route::post('/', [RedirectController::class, 'store'])->name('store');

        Route::post('actions/run', [RedirectActionController::class, 'run'])->name('actions.run');
        Route::post('actions/run/list', [RedirectActionController::class, 'bulkActions'])->name('actions.bulk');

        Route::get('/{id}', [RedirectController::class, 'edit'])->name('edit');
        Route::match(['post', 'patch'], '/{id}', [RedirectController::class, 'update'])->name('update');
        Route::delete('/{id}', [RedirectController::class, 'destroy'])->name('delete');
    });
});
