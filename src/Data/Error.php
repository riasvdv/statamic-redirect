<?php

namespace Rias\StatamicRedirect\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Error extends Model
{
    protected $guarded = [];

    protected $casts = [
        'handled' => 'boolean',
        'lastSeenAt' => 'timestamp',
        'hitsCount' => 'int',
    ];

    public $timestamps = false;

    public function getConnectionName()
    {
        return config('statamic.redirect.connection', 'redirect');
    }

    protected static function booted()
    {
        self::deleting(function ($error) {
            $error->hits()->delete();
        });
    }

    public function hits(): HasMany
    {
        return $this->hasMany(Hit::class, 'error_id');
    }

    public function addHit(int $timestamp, array $data = []): ?Hit
    {
        $this->update([
            'lastSeenAt' => $timestamp,
            'hitsCount' => $this->hitsCount + 1,
        ]);

        if (config('statamic.redirect.log_hits')) {
            return $this->hits()->create([
                'timestamp' => $timestamp,
                'data' => $data,
            ]);
        }

        return null;
    }

    public static function findByUrl(string $url): ?self
    {
        return self::where('url', $url)->first();
    }
}
