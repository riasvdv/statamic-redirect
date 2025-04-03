<?php

namespace Rias\StatamicRedirect\Stache\Redirects;

use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\YAML;
use Statamic\Stache\Stores\BasicStore;
use Statamic\Support\Arr;
use Symfony\Component\Finder\SplFileInfo;

class RedirectStore extends BasicStore
{
    protected $storeIndexes = [
        'source',
    ];

    public function key()
    {
        return 'redirects';
    }

    public function makeItemFromFile($path, $contents)
    {
        $data = YAML::file($path)->parse($contents);

        if (! $id = Arr::pull($data, 'id')) {
            $idGenerated = true;
            $id = app('stache')->generateId();
        }

        $redirect = Redirect::make()
            ->id($id)
            ->source(Arr::pull($data, 'source'))
            ->source_md5(Arr::pull($data, 'source_md5'))
            ->destination(Arr::pull($data, 'destination'))
            ->type(Arr::pull($data, 'type'))
            ->matchType(Arr::pull($data, 'match_type'))
            ->enabled(Arr::pull($data, 'enabled'))
            ->order(Arr::pull($data, 'order'))
            ->setSiteFromFilePath($path)
            ->initialPath($path);

        if (isset($idGenerated)) {
            $redirect->save();
        }

        return $redirect;
    }

    public function filter(SplFileInfo $file)
    {
        return $file->getExtension() === 'yaml';
    }
}
