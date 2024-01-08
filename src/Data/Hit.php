<?php

namespace Rias\StatamicRedirect\Data;

use Illuminate\Database\Eloquent\Model;

class Hit extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'timestamp' => 'timestamp',
        'data' => 'json',
    ];

    public function getConnectionName()
    {
        if (config('statamic.redirect.connection') !== null) {
            return config('statamic.redirect.connection');
        }

        if (config('statamic.redirect.error_connection') === 'default') {
            return config('database.default');
        }

        return config('statamic.redirect.error_connection', 'redirect-sqlite');
    }
}
