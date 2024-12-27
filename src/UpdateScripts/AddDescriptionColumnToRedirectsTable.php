<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class AddDescriptionColumnToRedirectsTable extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        if (config('statamic.redirect.redirect_connection') === 'stache') {
            return false;
        }

        try {
            return ! Schema::connection(config('statamic.redirect.redirect_connection'))->hasColumn('redirects', 'description');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-redirect-migrations',
        ]);

        $this->console()->info('New migration for Redirect description published, make sure to it!');
    }
}
