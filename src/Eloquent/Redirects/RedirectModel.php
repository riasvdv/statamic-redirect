<?php

namespace Rias\StatamicRedirect\Eloquent\Redirects;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RedirectModel extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [];

    protected $table = 'redirects';
}
