<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Models\Redirect;
use Statamic\CP\Column;

class RedirectController
{
    public function index()
    {
        $redirects = Redirect::all();

        return view('redirect::redirects.index', [
            'redirects' => $redirects,
            'columns'  => [
                Column::make('enabled')->label(''),
                Column::make('source')->label('Source'),
                Column::make('destination')->label('Destination'),
                Column::make('match_type')->label('Match type'),
                Column::make('type')->label('Redirect Type'),
            ],
        ]);
    }

    public function create()
    {
        $blueprint = new RedirectBlueprint();
        $fields    = $blueprint()->fields()->preProcess();

        return view('redirect::redirects.create', [
            'blueprint' => $blueprint()->toPublishArray(),
            'values'    => $fields->values()->merge([
                'source' => request('source'),
            ]),
            'meta'      => $fields->meta(),
        ]);
    }

    public function edit($redirect)
    {
        $redirect = Redirect::find($redirect);
        $redirectValues = $redirect->toArray();
        $redirectBlueprint = new RedirectBlueprint();
        $redirectFields = $redirectBlueprint()->fields()->addValues($redirectValues)->preProcess();

        return view('redirect::redirects.edit', [
            'blueprint' => $redirectBlueprint()->toPublishArray(),
            'values'    => $redirectFields->values(),
            'meta'      => $redirectFields->meta(),
        ]);
    }

    public function store(Request $request)
    {
        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();
        $values = $fields->process()->values();

        $redirect = Redirect::create($values->toArray());

        session()->flash('success', 'Redirect created successfully');

        return $redirect->slug;
    }

    public function update($redirect, Request $request)
    {
        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::find($redirect);
        $redirect->delete();

        $values = $fields->process()->values();

        $redirect = Redirect::create($values->toArray());

        session()->flash('success', 'Redirect updated successfully');

        return $redirect->slug;
    }

    public function destroy(string $slug)
    {
        $redirect = Redirect::find($slug);
        $redirect->delete();
    }
}
