<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class AddDescriptionColumnToRedirectsTable extends UpdateScript
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
            return ! Schema::connection($connection)->hasColumn('redirects', 'description');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        $connection = config('statamic.redirect.redirect_connection');

        if ($connection === 'redirect-sqlite') {
            Schema::connection($connection)->table('redirects', function (Blueprint $table): void {
                $table->text('description')->nullable()->after('enabled');
            });

            $this->console()->info('Added description field to the redirects table!');

            return;
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-redirect-migrations',
        ]);

        $this->console()->info('New migration for Redirect description published, make sure to run it!');
    }
}
