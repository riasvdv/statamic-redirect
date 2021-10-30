<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Redirect extends Model
{
    protected $guarded = [];

    protected $table = 'redirects';

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $keyType = 'uuid';
    protected $primaryKey = 'uuid';
}
