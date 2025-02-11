<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Data\Error;
use Statamic\UpdateScripts\UpdateScript;

class IncreaseUrlSizeOnErrors extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        $errorConnection = config('statamic.redirect.error_connection');

        if ($errorConnection === 'default') {
            $errorConnection = config('database.default');
        }

        try {
            return ! Schema::connection($errorConnection)->hasColumn('errors', 'url_md5');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        $errorConnection = config('statamic.redirect.error_connection');

        if ($errorConnection === 'redirect-sqlite') {
            if (Schema::connection($errorConnection)->hasIndex('errors', 'errors_url_index')) {
                Schema::connection($errorConnection)->table('errors', function (Blueprint $table): void {
                    $table->dropIndex('errors_url_index');
                });
            }

            Schema::connection($errorConnection)->table('errors', function (Blueprint $table): void {
                $table->string('url', 2048)->change();
                $table->string('url_md5')->index()->nullable()->after('url');
            });

            Error::each(function (Error $error) {
                $error->update(['url_md5' => md5($error->url)]);
            });

            $this->console()->info('Increased url column size in errors table.');

            return;
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-error-migrations',
        ]);

        $this->console()->info('New migration for Redirects published, make sure to run it!');
    }
}
