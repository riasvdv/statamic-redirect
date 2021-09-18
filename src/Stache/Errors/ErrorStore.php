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
            $filename = pathinfo($path, PATHINFO_FILENAME);
            unlink($path);
            $this->forgetItem($filename);
            $this->forgetPath($filename);
            $data = ['id' => 'error'];
        }

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
