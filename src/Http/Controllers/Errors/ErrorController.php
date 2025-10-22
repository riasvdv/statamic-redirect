<?php

namespace Rias\StatamicRedirect\Http\Controllers\Errors;

use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;
use Statamic\Facades\Scope;

class ErrorController
{
    public function index()
    {
        return Inertia::render('redirect::Errors/Index', [
            'filters' => Scope::filters('errors'),
            'clearUrl' => cp_route('redirect.errors.clear'),
            'actionUrl' => cp_route('redirect.errors.actions.run'),
        ]);
    }

    public function clearAll()
    {
        Error::query()->delete();

        session()->flash('success', __('Errors cleared!'));

        return redirect(cp_route('redirect.errors.index'), status: 303);
    }

    public function delete(Error $error)
    {
        $error->delete();

        session()->flash('success', __('Error deleted!'));

        return redirect(cp_route('redirect.errors.index'));
    }

    public function show(Error $error)
    {
        Breadcrumbs::push(new Breadcrumb(
            text: 'Error details',
        ));

        $title = "Error details - {$error->id}";

        return Inertia::render('redirect::Errors/Show', [
            'title' => $title,
            'createUrl' => cp_route('redirect.redirects.create'),
            'deleteUrl' => cp_route('redirect.errors.delete', $error),
            'error' => array_merge($error->toArray(), [
                'hits' => $error->hits->reverse()->map(function (Hit $hit) {
                    return array_merge($hit->toArray(), [
                        'timestampForHumans' => $hit->timestamp
                            ? Carbon::parse($hit->timestamp)->diffForHumans()
                            : null,
                    ]);
                })->values()->all(),
            ]),
        ]);
    }
}
