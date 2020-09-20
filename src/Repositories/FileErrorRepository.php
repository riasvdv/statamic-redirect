<?php

namespace Rias\StatamicRedirect\Repositories;

use Illuminate\Support\Facades\Date;
use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\DataTransferObjects\ErrorCollection;
use Statamic\Facades\File;

class FileErrorRepository extends FileRepository implements ErrorRepository
{
    public function all(): ErrorCollection
    {
        return parent::all();
    }

    public function find(int $id): ?Error
    {
        return parent::find($id);
    }

    protected function mapToCollection(array $data): ErrorCollection
    {
        return new ErrorCollection($data);
    }

    protected function mapToObject(array $data): Error
    {
        return new Error($data);
    }

    /**
     * @param Error $error
     * @return string
     */
    public function path($error): string
    {
        $date = Date::parse($error->date)->format('Y/m/d');

        return $this->basePath() . "/{$date}/{$error->id}.yaml";
    }

    public function basePath(): string
    {
        $path = storage_path('redirect/errors');

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }
}
