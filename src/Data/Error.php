<?php

namespace Rias\StatamicRedirect\Data;

use Illuminate\Database\Eloquent\JsonEncodingException;
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
        if (config('statamic.redirect.connection') === 'redirect') {
            return 'redirect-sqlite';
        }

        if (config('statamic.redirect.connection') !== null) {
            return config('statamic.redirect.connection');
        }

        if (config('statamic.redirect.error_connection') === 'default') {
            return config('database.default');
        }

        return config('statamic.redirect.error_connection', 'redirect-sqlite');
    }

    protected static function booted()
    {
        self::saving(function ($model) {
            $model->url_md5 = md5($model->url);
        });
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
            try {
                return $this->hits()->create([
                    'timestamp' => $timestamp,
                    'data' => $data,
                ]);
            } catch (JsonEncodingException $e) {
                // Malformed data or urls are probably from malicious requests
            }
        }

        return null;
    }

    public static function findByUrl(string $url): ?self
    {
        return self::where('url_md5', md5($url))->where('url', $url)->first();
    }
}
