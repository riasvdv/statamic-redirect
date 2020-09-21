<?php

namespace Rias\StatamicRedirect\Data;

use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Error
{
    use FluentlyGetsAndSets, ExistsAsFile, TracksQueriedColumns;

    /** @var string|int|null */
    protected $id;

    /** @var string */
    protected $url;

    /** @var array */
    protected $hits = [];

    /** @var bool */
    protected $handled = false;

    public function id($id = null)
    {
        return $this->fluentlyGetOrSet('id')->args(func_get_args());
    }

    public function url($url = null)
    {
        return $this->fluentlyGetOrSet('url')->args(func_get_args());
    }

    public function hits(array $hits = null)
    {
        return $this->fluentlyGetOrSet('hits')->args(func_get_args());
    }

    public function latest(): ?int
    {
        return collect($this->hits())->sortBy('timestamp')->pluck('timestamp')->last();
    }

    public function addHit(int $timestamp, array $data = [])
    {
        $this->hits[] = [
            'timestamp' => $timestamp,
            'data' => $data,
        ];

        return $this;
    }

    public function hitsCount(): int
    {
        if (! $this->hits()) {
            return 0;
        }

        return count($this->hits());
    }

    public function handled($handled = null)
    {
        return $this->fluentlyGetOrSet('handled')->args(func_get_args());
    }

    public function path()
    {
        return vsprintf('%s/%s.yaml', [
            rtrim(Stache::store('errors')->directory(), '/'),
            $this->id(),
        ]);
    }

    public function save()
    {
        \Rias\StatamicRedirect\Facades\Error::save($this);

        return true;
    }

    public function delete()
    {
        \Rias\StatamicRedirect\Facades\Error::delete($this);

        return true;
    }

    public function fileData()
    {
        return [
            'id' => $this->id(),
            'url' => $this->url(),
            'hits' => $this->hits(),
            'hitsCount' => $this->hitsCount(),
            'latest' => $this->latest(),
            'handled' => $this->handled(),
        ];
    }

    public function value($key)
    {
        return $this->get($key);
    }

    public function get($key, $fallback = null)
    {
        return $this->$key() ?? $fallback;
    }
}
