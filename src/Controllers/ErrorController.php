<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Eloquent\Errors\EloquentErrorRepository;
use Rias\StatamicRedirect\Eloquent\Errors\Error as EloquentError;
use Rias\StatamicRedirect\Facades\Error;
use Rias\StatamicRedirect\Stache\Errors\ErrorRepository as StacheErrorRepository;
use Statamic\Facades\Stache;

class ErrorController
{
    public function clearAll()
    {
        if (config('statamic.redirect.error_repository') === StacheErrorRepository::class) {
            File::deleteDirectory(storage_path('redirect/errors'));
        }

        if (config('statamic.redirect.error_repository') === EloquentErrorRepository::class) {
            EloquentError::query()->delete();
        }

        Stache::store('redirects')->clear();

        session()->flash('success', __('Errors cleared!'));

        return redirect(cp_route('redirect.index'));
    }

    public function delete(string $error)
    {
        $error = Error::find($error);

        if ($error) {
            $error->delete();
        }

        session()->flash('success', __('Error deleted!'));

        return redirect(cp_route('redirect.index'));
    }

    public function show(string $error)
    {
        $error = Error::find($error);
        $hits = $error->hits();
        $title = "Error details - {$error->id()}";

        return view('redirect::errors.show', compact('title', 'error', 'hits'));
    }
}
