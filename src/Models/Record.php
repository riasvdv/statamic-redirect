<?php

namespace Rias\StatamicRedirect\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Statamic\Facades\File;
use Statamic\Facades\YAML;

abstract class Record implements Arrayable, Jsonable
{
    protected static $identifier = 'id';

    protected abstract function __construct(array $attributes);
    public abstract static function make(array $attributes);
    public abstract static function create(array $attributes);
    public abstract static function all(): Collection;

    public static function find(string $id)
    {
        return static::all()->where(static::$identifier, $id)->first();
    }

    public abstract function toArray(): array;

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public abstract function path(): string;
    public abstract static function preparePath(): string;

    public function save()
    {
        File::put($this->path(), YAML::dump($this->toArray()));

        return $this;
    }

    public function delete()
    {
        File::delete($this->path());
    }
}
