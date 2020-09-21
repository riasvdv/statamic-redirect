<?php

namespace Rias\StatamicRedirect\Stache\Redirects;

use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\YAML;
use Statamic\Stache\Stores\BasicStore;
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

        if (! $id = array_pull($data, 'id')) {
            $idGenerated = true;
            $id = app('stache')->generateId();
        }

        $redirect = Redirect::make()
            ->id($id)
            ->source(array_pull($data, 'source'))
            ->destination(array_pull($data, 'destination'))
            ->type(array_pull($data, 'type'))
            ->matchType(array_pull($data, 'match_type'))
            ->enabled(array_pull($data, 'enabled'))
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
