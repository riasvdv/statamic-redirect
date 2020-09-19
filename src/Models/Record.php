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

    abstract protected function __construct(array $attributes);

    abstract public static function make(array $attributes);

    abstract public static function create(array $attributes);

    abstract public static function all(): Collection;

    public static function find(string $id)
    {
        return static::all()->where(static::$identifier, $id)->first();
    }

    abstract public function toArray(): array;

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    abstract public function path(): string;

    abstract public static function preparePath(): string;

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
