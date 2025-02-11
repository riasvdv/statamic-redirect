<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Eloquent\Redirects\RedirectModel;
use Statamic\UpdateScripts\UpdateScript;

class IncreaseUrlSizeOnRedirects extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        $redirectConnection = config('statamic.redirect.redirect_connection');

        if ($redirectConnection === 'stache') {
            return false;
        }

        if ($redirectConnection === 'default') {
            $redirectConnection = config('database.default');
        }

        try {
            return ! Schema::connection($redirectConnection)->hasColumn('redirects', 'source_md5');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        $redirectConnection = config('statamic.redirect.redirect_connection');

        if ($redirectConnection === 'redirect-sqlite') {
            if (Schema::connection($redirectConnection)->hasIndex('redirects', 'redirects_source_index')) {
                Schema::connection($redirectConnection)->table('redirects', function (Blueprint $table): void {
                    $table->dropIndex('redirects_source_index');
                });
            }

            Schema::connection($redirectConnection)->table('redirects', function (Blueprint $table): void {
                $table->string('source', 2048)->change();
                $table->string('source_md5')->index()->after('source');
                $table->string('destination', 2048)->change();
            });

            RedirectModel::each(function (RedirectModel $redirect) {
                $redirect->update(['source_md5' => md5($redirect->source)]);
            });

            $this->console()->info('Increased url column size in redirects table.');

            return;
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'statamic-redirect-redirect-migrations',
        ]);

        $this->console()->info('New migration for Redirects published, make sure to run it!');
    }
}
