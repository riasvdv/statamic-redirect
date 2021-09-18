<?php

namespace Rias\StatamicRedirect\Stache\Errors;

use Exception;
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
        try {
            $data = YAML::file($path)->parse($contents);
        } catch (Exception $e) {
            $uuid = pathinfo($path, PATHINFO_FILENAME);
            unlink($path);
            $this->forgetItem($uuid);
            $this->forgetPath($uuid);
            $data = ['id' => $uuid];
        }

        if (! $id = array_pull($data, 'id')) {
            $idGenerated = true;
            $id = app('stache')->generateId();
        }

        $error = Error::make()
            ->id($id)
            ->url(array_pull($data, 'url'))
            ->handled(array_pull($data, 'handled'))
            ->handledDestination(array_pull($data, 'handledDestination'))
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
