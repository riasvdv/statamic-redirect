<?php

namespace Rias\StatamicRedirect\Data;

use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Redirect
{
    use FluentlyGetsAndSets;
    use ExistsAsFile;
    use TracksQueriedColumns;

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
    protected $match_type = MatchTypeEnum::EXACT;

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
        return $this->fluentlyGetOrSet('match_type')->args(func_get_args());
    }

    public function path()
    {
        return vsprintf('%s/%s.yaml', [
            rtrim(Stache::store('redirects')->directory(), '/'),
            $this->id(),
        ]);
    }

    public function save()
    {
        \Rias\StatamicRedirect\Facades\Redirect::save($this);

        return true;
    }

    public function delete()
    {
        \Rias\StatamicRedirect\Facades\Redirect::delete($this);

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
