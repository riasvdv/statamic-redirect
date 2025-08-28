<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\ListedRedirect;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;
use Statamic\Facades\Scope;

class RedirectController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', RedirectContract::class);

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
        $this->authorize('create', RedirectContract::class);

        Breadcrumbs::push(new Breadcrumb(
            text: 'Create Redirect',
        ));

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

        $redirect = Redirect::find($id);

        $this->authorize('edit', $redirect);

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
        $this->authorize('create', RedirectContract::class);

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
            ->destination_type($request->get('destination_type'))
            ->destination_entry($request->get('destination_entry')[0] ?? null)
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
        $this->authorize('edit', RedirectContract::class);

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
            ->destination_type($request->get('destination_type'))
            ->destination_entry($request->get('destination_entry')[0] ?? null)
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
        $redirect = Redirect::find($id);

        abort_if(is_null($redirect), 404);

        $this->authorize('delete', $redirect);

        $redirect->delete();

        Cache::forget('statamic.redirect.redirects');
    }
}
