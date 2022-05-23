<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Http\Resources\ListedRedirect;
use Statamic\Facades\Scope;
use Statamic\Facades\Site;
use Statamic\Facades\User;

class RedirectController
{
    public function index(Request $request)
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('view redirects'), 401);

        $site = $request->site ? Site::get($request->site) : Site::selected();

        $blueprint = new RedirectBlueprint();
        $columns = $blueprint()
            ->columns()
            ->setPreferred("redirect.columns")
            ->rejectUnlisted()
            ->values();

        return view('redirect::redirects.index', [
            'site' => $site->handle(),
            'filters' => Scope::filters('redirects'),
            'columns' => $columns,
            'sites' => Site::all()->map(function (\Statamic\Sites\Site $site) {
                return [
                    'handle' => $site->handle(),
                    'name' => $site->name(),
                ];
            })->values()->all(),
        ]);
    }

    public function create()
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

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
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('edit redirects'), 401);

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
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $selectedSite = $request->session()->get('statamic.cp.selected-site', Site::current()->handle());

        $redirect = Redirect::make()
            ->locale($selectedSite)
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
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('edit redirects'), 401);

        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::find($id);

        $selectedSite = $request->session()->get('statamic.cp.selected-site', Site::current()->handle());

        if (! $redirect) {
            abort('404');
        }

        $redirect
            ->locale($selectedSite)
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
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('delete redirects'), 401);

        $redirect = Redirect::find($id);
        $redirect->delete();

        Cache::forget('statamic.redirect.redirects');
    }
}
