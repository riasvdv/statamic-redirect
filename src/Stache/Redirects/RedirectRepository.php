<?php

namespace Rias\StatamicRedirect\Stache\Redirects;

use Rias\StatamicRedirect\Contracts\RedirectRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Statamic\Data\DataCollection;
use Statamic\Facades\Stache as StacheFacade;
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

    public function findByUrl(string $siteHandle, string $url): ?Redirect
    {
        return $this->query()
            ->where('enabled', true)
            ->where('locale', $siteHandle)
            ->orderBy('order')
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

                if (strcmp(Str::start($redirect->source(), '/'), Str::start($url, '/')) === 0
                    || strcmp(Str::start($redirect->source(), '/'), Str::start($url . '/', '/')) === 0
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
            $redirect->id(StacheFacade::generateId());
        }

        StacheFacade::store('redirects')->save($redirect);

        event(new RedirectSaved($redirect));
    }

    public function delete($redirect)
    {
        StacheFacade::store('redirects')->delete($redirect);
        
        return true;
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