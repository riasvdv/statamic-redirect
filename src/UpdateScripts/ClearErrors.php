<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Support\Facades\File;
use Statamic\Facades\Stache;
use Statamic\UpdateScripts\UpdateScript;

class ClearErrors extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('1.10.0');
    }

    public function update()
    {
        File::deleteDirectory(storage_path('redirect/errors'));
        Stache::store('redirects')->clear();

        $this->console()->info('Cleared 404 errors.');
    }
}
