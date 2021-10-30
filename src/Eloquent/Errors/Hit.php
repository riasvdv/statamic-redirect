<?php

namespace Rias\StatamicRedirect\Eloquent\Errors;

use Illuminate\Database\Eloquent\Model;

class Hit extends Model
{
    protected $guarded = [];

    protected $table = 'redirect_error_hits';

    public $timestamps = false;

    protected $casts = [
        'timestamp' => 'timestamp',
        'data' => 'json',
    ];

    protected $keyType = 'uuid';
    protected $primaryKey = 'uuid';
}
