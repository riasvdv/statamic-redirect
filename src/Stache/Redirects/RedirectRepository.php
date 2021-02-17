<?php

namespace Rias\StatamicRedirect\Stache\Redirects;

use Rias\StatamicRedirect\Contracts\RedirectRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Data\DataCollection;
use Statamic\Stache\Stache;
use Statamic\Support\Str;

class RedirectRepository implements RepositoryContract
{
    /** @var \Statamic\Stache\Stache */
    protected $stache;

    /** @var \Statamic\Stache\Stores\Store */
    protected $store;

    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->store = $stache->store('redirects');
    }

    public function all(): DataCollection
    {
        return $this->query()->get();
    }

    public function find($id): ?Redirect
    {
        return $this->query()->where('id', $id)->first();
    }

    public function findByUrl(string $url): ?Redirect
    {
        return $this->query()
            ->where('enabled', true)
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

    public function save($redirect)
    {
        if (! $redirect->id()) {
            $redirect->id($this->stache->generateId());
        }

        $this->store->save($redirect);
    }

    public function delete($entry)
    {
        $this->store->delete($entry);
    }

    public function query()
    {
        return new RedirectQueryBuilder($this->store);
    }

    public function make(): Redirect
    {
        return new Redirect();
    }
}
