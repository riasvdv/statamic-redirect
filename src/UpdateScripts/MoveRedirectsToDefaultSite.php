<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\UpdateScripts\UpdateScript;
use Symfony\Component\Finder\Finder;

class MoveRedirectsToDefaultSite extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('2.3.0');
    }

    public function update()
    {
        $site = Site::default();

        $redirectsDirectory = Stache::store('redirects')->directory();

        $finder = Finder::create()
            ->in($redirectsDirectory)
            ->depth(0)
            ->name('*.yaml');

        File::ensureDirectoryExists($redirectsDirectory . $site->handle());

        foreach ($finder->files() as $file) {
            File::move($file->getPathname(), $file->getPath() . '/' . $site->handle() . '/' . $file->getFilename());
        }

        Stache::store('redirects')->clear();

        $this->console()->info('Moved redirects to default site folder');
    }
}
