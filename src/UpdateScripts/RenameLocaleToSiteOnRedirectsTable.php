<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class RenameLocaleToSiteOnRedirectsTable extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        try {
            return Schema::hasColumn('redirects', 'locale');
        } catch (QueryException) {
            // Query exception happens when database is not set up
            return false;
        }
    }

    public function update()
    {
        Schema::table('redirects', function ($table) {
            $table->renameColumn('locale', 'site');
            $table->index(['site']);
        });

        $this->console()->info('Column renamed succesfully!');
    }
}
