<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\Repositories\RedirectRepository;
use Statamic\CP\Column;

class RedirectController
{
    /** @var \Rias\StatamicRedirect\Repositories\RedirectRepository */
    private $redirectRepository;

    public function __construct(RedirectRepository $redirectRepository)
    {
        $this->redirectRepository = $redirectRepository;
    }

    public function index()
    {
        $redirects = collect($this->redirectRepository->all()->items());

        return view('redirect::redirects.index', [
            'redirects' => $redirects,
            'columns' => [
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
        $fields = $blueprint()->fields()->preProcess();

        return view('redirect::redirects.create', [
            'blueprint' => $blueprint()->toPublishArray(),
            'values' => $fields->values()->merge([
                'source' => request('source'),
            ]),
            'meta' => $fields->meta(),
        ]);
    }

    public function edit(int $id)
    {
        $redirect = $this->redirectRepository->find($id);
        $redirectValues = $redirect->toArray();
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
        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();
        $values = $fields->process()->values();

        $redirect = new Redirect($values->toArray());
        $this->redirectRepository->save($redirect);

        session()->flash('success', 'Redirect created successfully');

        Cache::forget('statamic.redirect.redirects');

        return $redirect->id;
    }

    public function update($id, Request $request)
    {
        $blueprint = new RedirectBlueprint();
        $fields = $blueprint()->fields()->addValues($request->all());
        $fields->validate();

        /** @var Redirect $redirect */
        $redirect = $this->redirectRepository->find($id);

        if (! $redirect) {
            abort('404');
        }

        $values = $fields->process()->values()->toArray();

        $this->redirectRepository->update($redirect, $values);

        session()->flash('success', 'Redirect updated successfully');

        Cache::forget('statamic.redirect.redirects');

        return $redirect->id;
    }

    public function destroy($id)
    {
        $redirect = $this->redirectRepository->find($id);
        $this->redirectRepository->delete($redirect);
        Cache::forget('statamic.redirect.redirects');
    }
}
