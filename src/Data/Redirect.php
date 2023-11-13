<?php

namespace Rias\StatamicRedirect\Data;

use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Data\Concerns\TracksQueriedRelations;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Statamic\Contracts\Data\Localization;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Redirect implements Localization, RedirectContract
{
    use FluentlyGetsAndSets;
    use ExistsAsFile;
    use TracksQueriedColumns;
    use TracksQueriedRelations;

    /** @var string|int|null */
    protected $id;

    /** @var bool */
    protected $enabled = true;

    /** @var string */
    protected $source;

    /** @var string */
    protected $destination;

    /** @var string */
    protected $type = '301';

    /** @var string */
    protected $locale;

    /** @var string */
    protected $matchType = MatchTypeEnum::EXACT;

    public function id($id = null)
    {
        return $this->fluentlyGetOrSet('id')->args(func_get_args());
    }

    public function enabled($enabled = null)
    {
        return $this->fluentlyGetOrSet('enabled')->args(func_get_args());
    }

    public function source($source = null)
    {
        return $this->fluentlyGetOrSet('source')->args(func_get_args());
    }

    public function destination($destination = null)
    {
        return $this->fluentlyGetOrSet('destination')->args(func_get_args());
    }

    public function type($type = null)
    {
        return $this->fluentlyGetOrSet('type')->args(func_get_args());
    }

    public function matchType($matchType = null)
    {
        return $this->fluentlyGetOrSet('matchType')->args(func_get_args());
    }

    public function order($order = null)
    {
        return $this->fluentlyGetOrSet('order')->args(func_get_args());
    }

    public function locale($locale = null)
    {
        return $this
            ->fluentlyGetOrSet('locale')
            ->setter(function ($locale) {
                return $locale instanceof \Statamic\Sites\Site ? $locale->handle() : $locale;
            })
            ->getter(function ($locale) {
                return $locale ?? Site::default()->handle();
            })
            ->args(func_get_args());
    }

    public function setLocaleFromFilePath($path)
    {
        $explodedPath = explode('/', $path);
        $locale = $explodedPath[count($explodedPath) - 2]; // locale is 2nd last path segment

        return $this->locale($locale);
    }

    public function site()
    {
        return Site::get($this->locale());
    }

    public function path()
    {
        return vsprintf('%s/%s/%s%s.yaml', [
            rtrim(Stache::store('redirects')->directory(), '/'),
            $this->locale(),
            ! is_null($this->order()) ? $this->order() . '_' : '',
            $this->id(),
        ]);
    }

    public function save()
    {
        if (! $this->id()) {
            $this->id(Stache::generateId());
        }

        Stache::store('redirects')->save($this);

        event(new RedirectSaved($this));

        return true;
    }

    public function delete()
    {
        Stache::store('redirects')->delete($this);

        return true;
    }

    public function fileData()
    {
        return [
            'id' => $this->id(),
            'enabled' => $this->enabled(),
            'source' => $this->source(),
            'destination' => $this->destination(),
            'type' => $this->type(),
            'match_type' => $this->matchType(),
            'order' => $this->order(),
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
