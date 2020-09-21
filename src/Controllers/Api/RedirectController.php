<?php

namespace Rias\StatamicRedirect\Controllers\Api;

use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\RedirectsCollection;
use Rias\StatamicRedirect\Stache\Redirects\RedirectQueryBuilder;
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
            $sortField = 'source';
            $sortDirection = 'asc';
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        }

        $redirects = $query->paginate(request('perPage'));

        return (new RedirectsCollection($redirects))
            ->columnPreferenceKey('redirects.columns')
            ->additional(['meta' => [
                'activeFilterBadges' => $activeFilterBadges,
            ]]);
    }

    protected function indexQuery(): RedirectQueryBuilder
    {
        $query = Redirect::query();

        if ($search = request('search')) {
            $query->where('source', 'like', '%'.$search.'%');
        }

        return $query;
    }
}
