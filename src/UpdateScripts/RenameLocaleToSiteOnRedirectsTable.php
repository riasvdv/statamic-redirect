<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class RenameLocaleToSiteOnRedirectsTable extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return Schema::hasColumn('redirects', 'locale');
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
