<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\ListedRedirect;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;
use Statamic\Facades\Scope;
use Statamic\Facades\User;

class RedirectController
{
    public function index()
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('view redirects'), 401);

        if (Redirect::all()->isEmpty()) {
            return view('redirect::redirects.empty');
        }

        $blueprint = new RedirectBlueprint;

        $columns = $blueprint()
            ->columns()
            ->setPreferred('redirect.columns')
            ->rejectUnlisted()
            ->values();

        return view('redirect::redirects.index', [
            'actionUrl' => cp_route('redirect.redirects.actions.run'),
            'filters' => Scope::filters('redirects'),
            'columns' => $columns,
        ]);
    }

    public function create()
    {
        Breadcrumbs::push(new Breadcrumb(
            text: 'Create Redirect',
        ));

        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

        $blueprint = new RedirectBlueprint;
        $fields = $blueprint()->fields()->preProcess();

        return view('statamic::publish.form', [
            'icon' => 'arrow-up-right',
            'title' => 'Create Redirect',
            'blueprint' => $blueprint()->toPublishArray(),
            'values' => $fields->values()->merge([
                'source' => request('source'),
            ]),
            'meta' => $fields->meta(),
            'submitUrl' => cp_route('redirect.redirects.store'),
            'submitMethod' => 'POST',
            'asConfig' => false,
            'readOnly' => false,
        ]);
    }

    public function edit($id)
    {
        Breadcrumbs::push(new Breadcrumb(
            text: 'Update Redirect',
        ));

        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('edit redirects'), 401);

        $redirect = Redirect::find($id);
        $redirectValues = $redirect->fileData();
        $redirectBlueprint = new RedirectBlueprint;
        $redirectFields = $redirectBlueprint()->fields()->addValues($redirectValues)->preProcess();

        return view('statamic::publish.form', [
            'icon' => 'arrow-up-right',
            'title' => 'Update Redirect',
            'blueprint' => $redirectBlueprint()->toPublishArray(),
            'values' => $redirectFields->values(),
            'meta' => $redirectFields->meta(),
            'submitUrl' => cp_route('redirect.redirects.update', $id),
            'submitMethod' => 'POST',
            'asConfig' => false,
            'readOnly' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

        if (empty($request->get('site'))) {
            abort(402, 'Site is required');
        }

        $blueprint = new RedirectBlueprint;
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::make()
            ->site($request->get('site')[0])
            ->source($request->get('source'))
            ->source_md5(md5($request->get('source')))
            ->destination($request->get('destination'))
            ->enabled($request->get('enabled'))
            ->type((int) $request->get('type'))
            ->matchType($request->get('match_type'))
            ->description($request->get('description'));

        $redirect->save();

        Cache::forget('statamic.redirect.redirects');

        return [
            'data' => new ListedRedirect($redirect),
            'redirect' => cp_route('redirect.redirects.edit', ['id' => $redirect->id()]),
        ];
    }

    public function update($id, Request $request)
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('edit redirects'), 401);

        $blueprint = new RedirectBlueprint;
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::find($id);

        if (! $redirect) {
            abort('404');
        }

        $redirect
            ->site($request->get('site')[0])
            ->source($request->get('source'))
            ->source_md5(md5($request->get('source')))
            ->destination($request->get('destination'))
            ->enabled($request->get('enabled'))
            ->type((int) $request->get('type'))
            ->matchType($request->get('match_type'))
            ->description($request->get('description'));

        $redirect->save();

        Cache::forget('statamic.redirect.redirects');

        return [
            'data' => new ListedRedirect($redirect),
        ];
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
