<?php

namespace Rias\StatamicRedirect\Stache\Errors;

use Statamic\Facades\File;
use Rias\StatamicRedirect\Contracts\ErrorRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Error;
use Statamic\Data\DataCollection;
use Statamic\Facades\YAML;
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

    public function delete($error)
    {
        File::delete($error->hitsPath());
        $this->store->delete($error);
    }

    public function hits($error)
    {
        return YAML::file($error->hitsPath())->parse() ?? [];
    }

    public function setHits($error, array $newHits)
    {
        $path = $error->hitsPath();

        File::put($path, YAML::file($path)->dump($newHits));
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
