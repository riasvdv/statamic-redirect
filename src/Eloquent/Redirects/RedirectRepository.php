<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Illuminate\Database\Eloquent\Model;
use Rias\StatamicRedirect\Contracts\RedirectRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Data\DataCollection;
use Statamic\Stache\Stache;

class RedirectRepository implements RepositoryContract
{
    protected $stache;

    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
    }

    public function all(): DataCollection
    {
        return $this->query()->get();
    }

    public function find($id): ?Redirect
    {
        return $this->query()->where('id', $id)->first();
    }

    public function findByUrl(string $siteHandle, string $url): ?Redirect
    {
        // Try and find a static redirect first as that's a quicker query
        $staticRedirect = $this->query()
            ->where('enabled', true)
            ->where('site', $siteHandle)
            ->where(function (RedirectQueryBuilder $query) use ($url) {
                $query
                    ->orWhere('source', $url)
                    ->orWhere('source', str($url)->start('/'))
                    ->orWhere('source', str($url)->finish('/'))
                    ->orWhere('source', str($url)->start('/')->finish('/'));
            })
            ->where('match_type', MatchTypeEnum::EXACT)
            ->orderBy('order')
            ->first();

        if ($staticRedirect) {
            return $staticRedirect;
        }

        return $this->query()
            ->where('enabled', true)
            ->where('site', $siteHandle)
            ->where('match_type', MatchTypeEnum::REGEX)
            ->orderBy('order')
            ->get()
            ->map(function (Redirect $redirect) use ($url) {
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

                return null;
            })
            ->filter()
            ->first();
    }

    public function save($redirect)
    {
        if (! $redirect->id()) {
            $redirect->id($this->stache->generateId());
        }

        $this->toModel($redirect)->save();
    }

    public function delete($entry)
    {
        $this->toModel($entry)?->delete();
    }

    public function query()
    {
        return new RedirectQueryBuilder(RedirectModel::query());
    }

    public function make(): Redirect
    {
        return new Redirect();
    }

    public static function fromModel(Model $model)
    {
        return (new Redirect)
            ->id($model->id)
            ->source($model->source)
            ->destination($model->destination)
            ->type($model->type)
            ->matchType($model->match_type)
            ->enabled($model->enabled)
            ->order($model->order)
            ->locale($model->site);
    }

    private function toModel(Redirect $redirect)
    {
        $properties = [
            'source' => $redirect->source(),
            'destination' => $redirect->destination(),
            'match_type' => $redirect->matchType(),
            'type' => $redirect->type(),
            'enabled' => $redirect->enabled(),
            'order' => $redirect->order(),
            'site' => $redirect->locale(),
        ];

        $model = RedirectModel::firstOrNew(['id' => $redirect->id()], $properties);

        if ($model->exists) {
            $model->fill($properties);
        }

        return $model;
    }
}
