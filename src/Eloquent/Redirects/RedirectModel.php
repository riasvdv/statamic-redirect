<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RedirectModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    protected $table = 'redirects';

    public function getConnectionName()
    {
        if (config('statamic.redirect.connection') !== null) {
            return config('statamic.redirect.connection');
        }

        if (config('statamic.redirect.redirect_connection') === 'default') {
            return config('database.default');
        }

        return config('statamic.redirect.redirect_connection', 'redirect-sqlite');
    }
}
