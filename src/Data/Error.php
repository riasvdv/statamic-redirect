<?php

namespace Rias\StatamicRedirect\Data;

use Rias\StatamicRedirect\Contracts\ErrorRepository;
use Rias\StatamicRedirect\Eloquent\Errors\Error as ErrorModel;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Error
{
    use FluentlyGetsAndSets;
    use ExistsAsFile;
    use TracksQueriedColumns;

    /** @var string|int|null */
    protected $id;

    /** @var string */
    protected $url;

    /** @var int */
    protected $hitsCount = 0;

    /** @var bool */
    protected $handled = false;

    /** @var string|null */
    protected $handledDestination = null;

    /** @var int|null */
    protected $lastSeenAt = null;

    public function id($id = null)
    {
        return $this->fluentlyGetOrSet('id')->args(func_get_args());
    }

    public function url($url = null)
    {
        return $this->fluentlyGetOrSet('url')->args(func_get_args());
    }

    public function hits(): array
    {
        $repository = resolve(ErrorRepository::class);

        return $repository->hits($this);
    }

    public function hitsCount(int $hitsCount = null)
    {
        return $this->fluentlyGetOrSet('hitsCount')->args(func_get_args());
    }

    public function lastSeenAt(int $lastSeenAt = null)
    {
        return $this->fluentlyGetOrSet('lastSeenAt')->args(func_get_args());
    }

    public function latest(): ?int
    {
        return $this->lastSeenAt() ?? collect($this->hits() ?? [])->sortBy('timestamp')->pluck('timestamp')->last();
    }

    public function addHit(int $timestamp, array $data = [])
    {
        if (! $this->id()) {
            $this->id(Stache::generateId());
        }

        if ($this->lastSeenAt() < $timestamp) {
            $this->lastSeenAt($timestamp);
        }

        if (config('statamic.redirect.log_hits', true)) {
            $hits = collect($this->hits())
                ->push([
                    'timestamp' => $timestamp,
                    'data' => $data,
                ])
                ->sortBy('timestamp')
                ->toArray();

            $this->setHits($hits);
        }

        $this->hitsCount($this->hitsCount + 1);

        return $this;
    }

    public function setHits(array $newHits)
    {
        $repository = resolve(ErrorRepository::class);

        return $repository->setHits($this, $newHits);
    }

    public function handled($handled = null)
    {
        return $this->fluentlyGetOrSet('handled')->args(func_get_args());
    }

    public function handledDestination($handledDestination = null)
    {
        return $this->fluentlyGetOrSet('handledDestination')->args(func_get_args());
    }

    public function hitsPath()
    {
        $id = $this->id();

        if (! $id) {
            $this->id($id = Stache::generateId());
        }

        return vsprintf('%s/%s/%s/%s_hits.yaml', [
            rtrim(Stache::store('errors')->directory(), '/'),
            $id[0] . $id[1],
            $id[2] . $id[3],
            $this->id(),
        ]);
    }

    public function path()
    {
        $id = $this->id();

        return vsprintf('%s/%s/%s/%s.yaml', [
            rtrim(Stache::store('errors')->directory(), '/'),
            $id[0] . $id[1],
            $id[2] . $id[3],
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

    public static function fromModel(ErrorModel $model): self
    {
        $error = new self();

        $error->id($model->uuid);
        $error->url($model->url);
        $error->handled($model->handled);
        $error->handledDestination($model->handled_destination);
        $error->lastSeenAt($model->last_seen_at);
        $error->hitsCount($model->hits_count);

        return $error;
    }

    public function fileData()
    {
        return [
            'id' => $this->id(),
            'url' => $this->url(),
            'hitsCount' => $this->hitsCount(),
            'latest' => $this->latest(),
            'handled' => $this->handled(),
            'handledDestination' => $this->handledDestination(),
            'lastSeenAt' => $this->lastSeenAt(),
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
