<?php

namespace Rias\StatamicRedirect\Stache\Errors;

use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Contracts\ErrorRepository as RepositoryContract;
use Statamic\Data\DataCollection;
use Statamic\Stache\Stache;

class ErrorRepository implements RepositoryContract
{
    /** @var \Statamic\Stache\Stache */
    protected $stache;

    /** @var \Statamic\Stache\Stores\Store */
    protected $store;

    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->store = $stache->store('errors');
    }

    public function all(): DataCollection
    {
        return $this->query()->get();
    }

    public function find($id): ?Error
    {
        return $this->query()->where('id', $id)->first();
    }

    public function findByUrl(string $url): ?Error
    {
        return $this->query()
            ->where('url', $url)
            ->first();
    }

    public function save($error)
    {
        if (! $error->id()) {
            $error->id($this->stache->generateId());
        }

        $this->store->save($error);
    }

    public function delete($entry)
    {
        $this->store->delete($entry);
    }

    public function query()
    {
        return new ErrorQueryBuilder($this->store);
    }

    public function make(): Error
    {
        return new Error();
    }

}
