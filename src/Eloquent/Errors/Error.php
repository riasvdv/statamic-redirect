<?php

namespace Rias\StatamicRedirect\Eloquent\Errors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Error extends Model
{
    protected $guarded = [];

    protected $table = 'redirect_errors';

    protected $casts = [
        'handled' => 'boolean',
        'last_seen_at' => 'timestamp',
    ];

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::deleting(function () {
            $this->hits()->delete();
        });
    }

    public function hits(): HasMany
    {
        return $this->hasMany(Hit::class, 'error_uuid');
    }
}
