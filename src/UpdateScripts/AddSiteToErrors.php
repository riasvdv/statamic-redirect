<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Data\Error;
use Statamic\Facades\Site;
use Statamic\UpdateScripts\UpdateScript;

class AddSiteToErrors extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        $connection = config('statamic.redirect.error_connection');

        if ($connection === 'default') {
            $connection = config('database.default');
        }

        try {
            return ! Schema::connection($connection)->hasColumn('errors', 'site');
        } catch (QueryException) {
            return false;
        }
    }

    public function update(): void
    {
        $connection = config('statamic.redirect.error_connection');

        if ($connection === 'redirect-sqlite') {
            Schema::connection($connection)->table('errors', function (Blueprint $table): void {
                $table->string('site')->nullable()->index()->after('url_md5');
            });

            Error::query()->whereNull('site')->update(['site' => Site::default()->handle()]);

            $this->console()->info('Added the site column to the errors table.');

            return;
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-error-migrations',
        ]);

        $this->console()->info('New Redirect error migration published, make sure to run it!');
    }
}
