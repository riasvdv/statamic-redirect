<?php

namespace Rias\StatamicRedirect\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Statamic\Facades\File;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;

class Error extends Record
{
    /** @var int */
    public $id;

    /** @var string */
    public $url;

    /** @var int */
    public $date;

    /** @var bool */
    public $handled;

    protected function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? Str::uuid();
        $this->date = $attributes['date'] ?? now()->timestamp;
        $this->handled = $attributes['handled'] ?? false;
        $this->url = $attributes['url'];
    }

    public static function create(array $attributes)
    {
        return self::make($attributes)->save();
    }

    public static function make(array $attributes)
    {
        return new self($attributes);
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'url' => $this->url,
            'date' => $this->date,
            'handled' => $this->handled,
        ];
    }

    public static function all(): Collection
    {
        $files = collect(Folder::getFiles(static::preparePath(), true));

        if ($files->isEmpty()) {
            return $files;
        }

        return $files
            ->map(function ($path) {
                try {
                    return YAML::parse(File::get($path));
                } catch (\Exception $e) {
                    // Something went wrong while parsing, ignore
                    return null;
                }
            })
            ->filter()
            ->map(function ($data) {
                return static::make($data);
            })
            ->sortBy(function (Error $error) {
                return $error->date;
            })
            ->values();
    }

    public function path(): string
    {
        $date = Date::parse($this->date)->format('Y/m/d');

        return self::preparePath() . "/{$date}/{$this->id}.yaml";
    }

    public static function preparePath(): string
    {
        $path = collect([storage_path('redirect/errors')])->filter()->implode('/');

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }

    public function delete()
    {
        File::delete($this->path());
    }
}
