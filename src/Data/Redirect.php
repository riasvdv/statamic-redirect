<?php

namespace Rias\StatamicRedirect\Data;

use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Data\Concerns\TracksQueriedRelations;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect as RedirectFacade;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Redirect implements RedirectContract
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
    protected $source_md5;

    /** @var string */
    protected $destination;

    /** @var int */
    protected $type = 301;

    /** @var string */
    protected $site;

    /** @var string */
    protected $matchType = MatchTypeEnum::EXACT;

    /** @var int */
    protected $order;

    /** @var string|null */
    protected $description;

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

    public function source_md5($source_md5 = null)
    {
        return $this->fluentlyGetOrSet('source_md5')->args(func_get_args());
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

    public function setSiteFromFilePath($path)
    {
        $explodedPath = explode('/', $path);
        $site = $explodedPath[count($explodedPath) - 2]; // site is 2nd last path segment

        return $this->site($site);
    }

    public function site($site = null)
    {
        return $this
            ->fluentlyGetOrSet('site')
            ->setter(function ($site) {
                $siteHandle = $site instanceof \Statamic\Sites\Site ? $site->handle() : $site;

                $this->site = $siteHandle;

                return $siteHandle;
            })
            ->getter(function ($site) {
                return $site ?? Site::default()->handle();
            })
            ->args(func_get_args());
    }

    public function path()
    {
        return vsprintf('%s/%s/%s%s.yaml', [
            rtrim(Stache::store('redirects')->directory(), '/'),
            $this->site(),
            ! is_null($this->order()) ? $this->order() . '_' : '',
            $this->id(),
        ]);
    }

    public function description($matchType = null)
    {
        return $this->fluentlyGetOrSet('description')->args(func_get_args());
    }

    public function save()
    {
        return RedirectFacade::save($this);
    }

    public function delete()
    {
        return RedirectFacade::delete($this);
    }

    public function fileData()
    {
        return [
            'id' => $this->id(),
            'enabled' => $this->enabled(),
            'source' => $this->source(),
            'source_md5' => $this->source_md5(),
            'destination' => $this->destination(),
            'type' => $this->type(),
            'site' => $this->site(),
            'match_type' => $this->matchType(),
            'description' => $this->description(),
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
