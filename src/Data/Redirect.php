<?php

namespace Rias\StatamicRedirect\Data;

use Illuminate\Support\Str;
use Rias\StatamicRedirect\Data\Concerns\TracksQueriedRelations;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Stache\Redirects\RedirectQueryBuilder;
use Statamic\Contracts\Data\Localization;
use Statamic\Data\DataCollection;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;

class Redirect implements Localization
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

    public static function make()
    {
        return new self();
    }

    public static function query(): RedirectQueryBuilder
    {
        return new RedirectQueryBuilder(Stache::store('redirects'));
    }

    public static function all(): DataCollection
    {
        return self::query()->get();
    }

    public static function find($id): ?self
    {
        return self::query()->where('id', $id)->first();
    }

    public static function findByUrl(string $siteHandle, string $url): ?Redirect
    {
        return self::query()
            ->where('enabled', true)
            ->where('locale', $siteHandle)
            ->get()
            ->map(function (Redirect $redirect) use ($url) {
                if ($redirect->matchType() === MatchTypeEnum::REGEX) {
                    $source = str_replace('/', "\/", $redirect->source());
                    $matchRegEx = '`'.$source.'`i';

                    if (preg_match($matchRegEx, $url) === 1) {
                        $redirect->destination(preg_replace(
                            $matchRegEx,
                            $redirect->destination(),
                            $url
                        ));

                        return $redirect;
                    }
                }

                if (strcasecmp(Str::start($redirect->source(), '/'), Str::start($url, '/')) === 0
                    || strcasecmp(Str::start($redirect->source(), '/'), Str::start($url . '/', '/')) === 0
                ) {
                    return $redirect;
                }

                return null;
            })
            ->filter()
            ->first();
    }

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

    public function site()
    {
        return Site::get($this->locale());
    }

    public function path()
    {
        return vsprintf('%s/%s/%s.yaml', [
            rtrim(Stache::store('redirects')->directory(), '/'),
            $this->locale(),
            $this->id(),
        ]);
    }

    public function save()
    {
        if (! $this->id()) {
            $this->id(Stache::generateId());
        }

        Stache::store('redirects')->save($this);

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
