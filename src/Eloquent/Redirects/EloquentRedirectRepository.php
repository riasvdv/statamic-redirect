<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rias\StatamicRedirect\Contracts\RedirectRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Eloquent\Redirects\Redirect as RedirectModel;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Data\DataCollection;

class EloquentRedirectRepository implements RepositoryContract
{
    public function all(): DataCollection
    {
        return DataCollection::make($this->query()->get()->map(function (RedirectModel $model) {
            return Redirect::fromArray($model->toArray());
        }));
    }

    public function find($id): ?Redirect
    {
        $redirect = $this->query()->where('id', $id)->first();

        if (! $redirect) {
            return null;
        }

        return Redirect::fromArray($redirect->toArray());
    }

    public function findByUrl(string $url): ?Redirect
    {
        $redirect = $this->query()
            ->where('enabled', true)
            ->get()
            ->map(function (RedirectModel $model) {
                return Redirect::fromArray($model->toArray());
            })
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

                if (strcasecmp(\Statamic\Support\Str::start($redirect->source(), '/'), Str::start($url, '/')) === 0
                    || strcasecmp(Str::start($redirect->source(), '/'), Str::start($url . '/', '/')) === 0
                ) {
                    return $redirect;
                }

                return null;
            })
            ->filter()
            ->first();

        return $redirect;
    }

    /** @var Redirect $redirect */
    public function save($redirect)
    {
        if (! $redirect->id()) {
            $redirect->id(Str::uuid()->toString());
        }

        DB::table('redirects')
            ->updateOrInsert([
                'uuid' => $redirect->id(),
            ], [
                'enabled' => $redirect->enabled(),
                'source' => $redirect->source(),
                'destination' => $redirect->destination(),
                'type' => $redirect->type(),
                'match_type' => $redirect->matchType(),
            ]);
    }

    /** @var Redirect $redirect */
    public function delete($redirect)
    {
        RedirectModel::query()
            ->where('id', $redirect->id())
            ->delete();
    }

    public function query()
    {
        return RedirectModel::query();
    }

    public function make(): Redirect
    {
        return new Redirect;
    }
}
