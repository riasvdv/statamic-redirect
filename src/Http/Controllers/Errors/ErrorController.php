<?php

namespace Rias\StatamicRedirect\Http\Controllers\Errors;

use Rias\StatamicRedirect\Data\Error;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;
use Statamic\Facades\Scope;

class ErrorController
{
    public function index()
    {
        return view('redirect::errors.index', [
            'filters' => Scope::filters('errors'),
            'actionUrl' => cp_route('redirect.errors.actions.run'),
        ]);
    }

    public function clearAll()
    {
        Error::query()->delete();

        session()->flash('success', __('Errors cleared!'));

        return redirect()->back();
    }

    public function delete(Error $error)
    {
        $error->delete();

        session()->flash('success', __('Error deleted!'));

        return redirect(cp_route('redirect.index'));
    }

    public function show(Error $error)
    {
        Breadcrumbs::push(new Breadcrumb(
            text: 'Error details',
        ));

        $title = "Error details - {$error->id}";

        return view('redirect::errors.show', compact('title', 'error'));
    }
}
