<?php

namespace Rias\StatamicRedirect\Controllers\Api;

use Rias\StatamicRedirect\Facades\Error;
use Rias\StatamicRedirect\Http\Resources\ErrorsCollection;
use Rias\StatamicRedirect\Stache\Errors\ErrorQueryBuilder;
use Statamic\Http\Requests\FilteredRequest;
use Statamic\Query\Scopes\Filters\Concerns\QueriesFilters;

class ErrorController
{
    use QueriesFilters;

    public function index(FilteredRequest $request)
    {
        $query = $this->indexQuery();
        $activeFilterBadges = $this->queryFilters($query, $request->filters);

        $sortField = request('sort');
        $sortDirection = request('order', 'asc');

        if (! $sortField && ! request('search')) {
            $sortField = 'latest';
            $sortDirection = 'desc';
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        }

        $errors = $query->paginate(request('perPage'));

        return (new ErrorsCollection($errors))
            ->columnPreferenceKey('errors.columns')
            ->additional(['meta' => [
                'activeFilterBadges' => $activeFilterBadges,
            ]]);
    }

    protected function indexQuery(): ErrorQueryBuilder
    {
        $query = Error::query();

        if ($search = request('search')) {
            $query->where('url', 'like', '%'.$search.'%');
        }

        return $query;
    }
}
