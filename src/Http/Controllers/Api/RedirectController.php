<?php

namespace Rias\StatamicRedirect\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Rias\StatamicRedirect\Contracts\RedirectQueryBuilder;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\RedirectsCollection;
use Statamic\Http\Requests\FilteredRequest;
use Statamic\Query\Scopes\Filters\Concerns\QueriesFilters;

class RedirectController
{
    use QueriesFilters;

    public function index(FilteredRequest $request)
    {
        $query = $this->indexQuery();
        $activeFilterBadges = $this->queryFilters($query, $request->filters);

        $sortField = request('sort');
        $sortDirection = request('order', 'asc');

        if (! $sortField && ! request('search')) {
            $sortField = 'order';
            $sortDirection = 'asc';
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        }

        $redirects = $query->paginate(request('perPage'));

        return (new RedirectsCollection($redirects))
            ->columnPreferenceKey('redirect.redirects.columns')
            ->additional(['meta' => [
                'activeFilterBadges' => $activeFilterBadges,
            ]]);
    }

    public function reorder(): JsonResponse
    {
        foreach (request('redirects') as $order => $data) {
            $redirect = Redirect::find($data['id']);
            if (! $redirect) {
                continue;
            }
            $redirect->order($order);
            $redirect->save();
        }

        return response()->json();
    }

    protected function indexQuery(): RedirectQueryBuilder
    {
        $query = Redirect::query();

        if ($search = request('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('source', 'like', '%'.$search.'%');
                $query->orWhere('destination', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }
}
