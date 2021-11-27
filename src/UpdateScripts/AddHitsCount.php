<?php

namespace Rias\StatamicRedirect\UpdateScripts;

use Illuminate\Support\Facades\Schema;
use Statamic\UpdateScripts\UpdateScript;

class AddHitsCount extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        try {
            Schema::getConnection()->getPdo();
        } catch (\Exception $e) {
            return false; // No database connection
        }

        if (! Schema::hasTable('redirect_errors')) {
            return false; // No redirect migrations
        }

        if (Schema::hasColumn('redirect_errors', 'hits_count')) {
            return false; // Column already exists
        }

        return true;
    }

    public function update()
    {
        Schema::table('redirect_errors', function ($table) {
            $table->integer('hits_count')->nullable();
        });

        $this->console()->info('New column added succesfully!');
    }
}
