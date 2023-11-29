<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Rias\StatamicRedirect\Contracts\RedirectQueryBuilder as Contract;
use Statamic\Data\DataCollection;
use Statamic\Query\EloquentQueryBuilder;

class RedirectQueryBuilder extends EloquentQueryBuilder implements Contract
{
    protected function transform($items, $columns = [])
    {
        return DataCollection::make($items)->map(function ($model) use ($columns) {
            return RedirectRepository::fromModel($model);
        });
    }
}
