<?php

namespace Rias\StatamicRedirect\Repositories;

use Illuminate\Support\Arr;
use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\DataTransferObjects\RedirectCollection;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Facades\File;
use Statamic\Support\Str;

class FileRedirectRepository extends FileRepository implements RedirectRepository
{
    public function all(): RedirectCollection
    {
        return parent::all();
    }

    public function find(int $id): ?Redirect
    {
        return parent::find($id);
    }

    /**
     * @param Redirect $redirect
     * @param array $data
     *
     * @return \Rias\StatamicRedirect\DataTransferObjects\Redirect
     */
    public function update($redirect, array $data): Redirect
    {
        return parent::update($redirect, $data);
    }

    public function findForUrl(string $url): ?Redirect
    {
        return collect($this->all()->items())
            ->where('enabled', true)
            ->map(function (Redirect $redirect) use ($url) {
                if ($redirect->match_type === MatchTypeEnum::REGEX) {
                    $source = str_replace('/', "\/", $redirect->source);
                    $matchRegEx = '`'.$source.'`i';

                    if (preg_match($matchRegEx, $url) === 1) {
                        $redirect->destination = preg_replace(
                            $matchRegEx,
                            $redirect->destination,
                            $url
                        );

                        return $redirect;
                    }
                }

                if (strcasecmp(Str::start($redirect->source, '/'), Str::start($url, '/')) === 0) {
                    return $redirect;
                }

                return null;
            })
            ->filter()
            ->first();
    }

    protected function mapToCollection(array $data): RedirectCollection
    {
        return new RedirectCollection($data);
    }

    protected function mapToObject(array $data): Redirect
    {
        return new Redirect(Arr::except($data, 'slug'));
    }

    /**
     * @param Redirect $redirect
     * @return string
     */
    public function path($redirect): string
    {
        return base_path("content/redirects/{$redirect->id}.yaml");
    }

    public function basePath(): string
    {
        $path = base_path('content/redirects');

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }
}
