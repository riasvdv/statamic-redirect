<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\ListedRedirect;
use Statamic\CP\Breadcrumbs\Breadcrumb;
use Statamic\CP\Breadcrumbs\Breadcrumbs;
use Statamic\Facades\Scope;
use Statamic\Facades\Site;

class RedirectController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', RedirectContract::class);

        if (Redirect::all()->isEmpty()) {
            return Inertia::render('redirect::Redirects/Empty', [
                'createUrl' => cp_route('redirect.redirects.create'),
            ]);
        }

        $blueprint = new RedirectBlueprint;

        $columns = $blueprint()
            ->columns()
            ->setPreferred('redirect.columns')
            ->rejectUnlisted()
            ->values();

        return Inertia::render('redirect::Redirects/Index', [
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

        return Inertia::render('redirect::Redirects/Publish', [
            'isCreating' => true,
            'icon' => 'arrow-up-right',
            'title' => __('Create Redirect'),
            'blueprint' => $blueprint()->toPublishArray(),
            'values' => $fields->values()->merge([
                'source' => request('source'),
            ])->all(),
            'submitUrl' => cp_route('redirect.redirects.store'),
            'meta' => $fields->meta()->all(),
            'asConfig' => false,
            'listingUrl' => cp_route('redirect.redirects.index'),
            'createUrl' => cp_route('redirect.redirects.create'),
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

        return Inertia::render('redirect::Redirects/Publish', [
            'isCreating' => false,
            'icon' => 'arrow-up-right',
            'title' => __('Update Redirect'),
            'blueprint' => $redirectBlueprint()->toPublishArray(),
            'values' => $redirectFields->values(),
            'submitUrl' => cp_route('redirect.redirects.update', $id),
            'meta' => $redirectFields->meta(),
            'asConfig' => false,
            'listingUrl' => cp_route('redirect.redirects.index'),
            'createUrl' => cp_route('redirect.redirects.create'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', RedirectContract::class);

        $blueprint = new RedirectBlueprint;
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        $redirect = Redirect::make()
            ->site(Site::selected()->handle())
            ->source($request->get('source'))
            ->source_md5(md5($request->get('source')))
            ->destination($request->get('destination'))
            ->destination_type('url')
            ->destination_entry(null)
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
            ->source($request->get('source'))
            ->source_md5(md5($request->get('source')))
            ->destination($request->get('destination'))
            ->destination_type('url')
            ->destination_entry(null)
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
