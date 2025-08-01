<?php

namespace Rias\StatamicRedirect\Http\Controllers;

use Rias\StatamicRedirect\Data\Error;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;

class ErrorController
{
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
            text: 'Redirect',
            url: cp_route('redirect.index'),
        ));

        Breadcrumbs::push(new Breadcrumb(
            text: 'Errors',
            url: cp_route('redirect.index'),
        ));

        Breadcrumbs::push(new Breadcrumb(
            text: 'Error details',
        ));

        $title = "Error details - {$error->id}";

        return view('redirect::errors.show', compact('title', 'error'));
    }
}
