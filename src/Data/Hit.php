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
        return config('statamic.redirect.connection', 'redirect');
    }
}
