<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class Version4Upgrade extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        $connection = config('statamic.redirect.redirect_connection');

        if ($connection === 'stache') {
            return false;
        }

        if ($connection === 'default') {
            $connection = config('database.default');
        }

        try {
            return ! Schema::connection($connection)->hasColumn('redirects', 'last_used_at');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        $connection = config('statamic.redirect.redirect_connection');

        if ($connection === 'redirect-sqlite') {
            require_once __DIR__.'/../../database/migrations/version_4_upgrade.php.stub';
            (new \Version4UpgradeMigration)->up();

            $this->console()->info('Migrated to v4!');

            return;
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-redirect-migrations',
        ]);

        $this->console()->info('New migration for Redirect published, make sure to run it!');
    }
}
