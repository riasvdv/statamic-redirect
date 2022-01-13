<?php

namespace Rias\StatamicRedirect\Stache\Errors;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Rias\StatamicRedirect\Facades\Error;
use Statamic\Facades\File;
use Statamic\Facades\Path;
use Statamic\Facades\Stache;
use Statamic\Facades\YAML;
use Statamic\Stache\Exceptions\DuplicateKeyException;
use Statamic\Stache\Stores\BasicStore;
use Statamic\Stache\Traverser;
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

    public function getItemFilter(SplFileInfo $file)
    {
        $filename = str_after(Path::tidy($file->getPathName()), $this->directory);

        return !Str::contains($filename, '_hits') && $file->getExtension() === 'yaml';
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
            ->hitsCount(array_pull($data, 'hitsCount'))
            ->initialPath($path);

        if (isset($idGenerated)) {
            $error->save();
        }

        return $error;
    }
}
