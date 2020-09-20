<?php

namespace Rias\StatamicRedirect\Repositories;

use Statamic\Facades\File;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;

abstract class FileRepository
{
    abstract public function path($object): string;

    abstract public function basePath(): string;

    abstract protected function mapToObject(array $data);

    abstract protected function mapToCollection(array $data);

    public function all()
    {
        $files = collect(Folder::getFiles($this->basePath(), true));

        if ($files->isEmpty()) {
            return $this->mapToCollection([]);
        }

        $errors = $files
            ->map(function ($path, $index) {
                try {
                    $data = YAML::parse(File::get($path));

                    /** For backwards compatibility if an entry has no id */
                    if (! isset($data['id']) || ! $data['id']) {
                        $data['id'] = $index + 1;
                        File::delete($path, YAML::dump($data));
                        File::put($this->path($this->mapToObject($data)), YAML::dump($data));
                    }

                    return $data;
                } catch (\Exception $e) {
                    // Something went wrong while parsing, ignore
                    return null;
                }
            })
            ->filter()
            ->map(function ($data) {
                return $this->mapToObject($data);
            })
            ->values()
            ->all();

        return $this->mapToCollection($errors);
    }

    public function save($object): void
    {
        if (! $object->id) {
            $object->id = $this->nextId();
        }

        File::put($this->path($object), YAML::dump($object->toArray()));
    }

    public function update($object, array $data)
    {
        if (! $object->id) {
            $object->id = $this->nextId();
        }

        $this->delete($object);

        foreach ($data as $key => $value) {
            $object->$key = $value;
        }

        File::put($this->path($object), YAML::dump($object->toArray()));

        return $object;
    }

    public function find(int $id)
    {
        return collect($this->all()->items())->where('id', $id)->first();
    }

    public function nextId(): int
    {
        $last = collect($this->all()->items())->sortBy('id')->pluck('id')->filter(function ($id) {
            return is_numeric($id);
        })->last();

        if (! $last) {
            return 1;
        }

        return $last + 1;
    }

    public function delete($object): void
    {
        File::delete($this->path($object));
    }
}
