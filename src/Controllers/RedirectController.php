<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\ListedRedirect;
use Statamic\Facades\Scope;

class RedirectController
{
    public function index()
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('view redirects'), 401);

        return view('redirect::redirects.index', [
            'filters' => Scope::filters('redirects'),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects'), 401);

        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->preProcess();

        return view('redirect::redirects.create', [
            'blueprint' => $blueprint()->toPublishArray(),
            'values' => $fields->values()->merge([
                'source' => request('source'),
            ]),
            'meta' => $fields->meta(),
        ]);
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('edit redirects'), 401);

        $redirect = Redirect::find($id);
        $redirectValues = $redirect->fileData();
        $redirectBlueprint = new RedirectBlueprint();
        $redirectFields = $redirectBlueprint()->fields()->addValues($redirectValues)->preProcess();

        return view('redirect::redirects.edit', [
            'redirect' => $redirect,
            'blueprint' => $redirectBlueprint()->toPublishArray(),
            'values' => $redirectFields->values(),
            'meta' => $redirectFields->meta(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects'), 401);

        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::make()
            ->source($request->get('source'))
            ->destination($request->get('destination'))
            ->enabled($request->get('enabled'))
            ->type($request->get('type'))
            ->matchType($request->get('match_type'));

        $redirect->save();

        session()->flash('success', 'Redirect created successfully');

        Cache::forget('statamic.redirect.redirects');

        return new ListedRedirect($redirect);
    }

    public function update($id, Request $request)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('edit redirects'), 401);

        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::find($id);

        if (! $redirect) {
            abort('404');
        }

        $redirect
            ->source($request->get('source'))
            ->destination($request->get('destination'))
            ->enabled($request->get('enabled'))
            ->type($request->get('type'))
            ->matchType($request->get('match_type'));

        $redirect->save();

        session()->flash('success', 'Redirect updated successfully');

        Cache::forget('statamic.redirect.redirects');

        return new ListedRedirect($redirect);
    }

    public function destroy($id)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('delete redirects'), 401);

        $redirect = Redirect::find($id);
        $redirect->delete();

        Cache::forget('statamic.redirect.redirects');
    }
}
