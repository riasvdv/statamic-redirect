<?php

namespace Rias\StatamicRedirect\Controllers;

use Rias\StatamicRedirect\Eloquent\Errors\Error;

class ErrorController
{
    public function clearAll()
    {
        Error::query()->delete();

        session()->flash('success', __('Errors cleared!'));

        return redirect(cp_route('redirect.index'));
    }

    public function delete(Error $error)
    {
        $error->delete();

        session()->flash('success', __('Error deleted!'));

        return redirect(cp_route('redirect.index'));
    }

    public function show(Error $error)
    {
        $hits = $error->hits;
        $title = "Error details - {$error->id}";

        return view('redirect::errors.show', compact('title', 'error', 'hits'));
    }
}
