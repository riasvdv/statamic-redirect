<?php

namespace Rias\StatamicRedirect\Stache\Errors;

use Rias\StatamicRedirect\Facades\Error;
use Statamic\Facades\YAML;
use Statamic\Stache\Stores\BasicStore;
use Symfony\Component\Finder\SplFileInfo;

class ErrorStore extends BasicStore
{
    protected $storeIndexes = [
        'url',
    ];

    public function key()
    {
        return 'errors';
    }

    public function makeItemFromFile($path, $contents)
    {
        $data = YAML::file($path)->parse($contents);

        if (! $id = array_pull($data, 'id')) {
            $idGenerated = true;
            $id = app('stache')->generateId();
        }

        $error = Error::make()
            ->id($id)
            ->url(array_pull($data, 'url'))
            ->handled(array_pull($data, 'handled'))
            ->hits(array_pull($data, 'hits'))
            ->initialPath($path);

        if (isset($idGenerated)) {
            $error->save();
        }

        return $error;
    }

    public function filter(SplFileInfo $file)
    {
        return $file->getExtension() === 'yaml';
    }
}
