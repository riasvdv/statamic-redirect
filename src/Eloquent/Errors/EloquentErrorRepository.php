<?php

namespace Rias\StatamicRedirect\Eloquent\Errors;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rias\StatamicRedirect\Contracts\ErrorRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Eloquent\Errors\Error as ErrorModel;
use Statamic\Data\DataCollection;

class EloquentErrorRepository implements RepositoryContract
{
    public function all(): DataCollection
    {
        return DataCollection::make($this->query()->get()->map(function (ErrorModel $model) {
            return Error::fromModel($model);
        }));
    }

    public function find($id): ?Error
    {
        $error = $this->query()
            ->where('uuid', $id)
            ->first();

        if (! $error) {
            return null;
        }

        return Error::fromModel($error);
    }

    public function findByUrl(string $url): ?Error
    {
        $error = $this->query()
            ->where('url', $url)
            ->first();

        if (! $error) {
            return null;
        }

        return Error::fromModel($error);
    }

    /** @var Error $error */
    public function save($error)
    {
        if (! $error->id()) {
            $error->id(Str::uuid()->toString());
        }

        DB::table('redirect_errors')
            ->updateOrInsert([
                'uuid' => $error->id(),
            ], [
                'url' => $error->url(),
                'handled' => $error->handled(),
                'handled_destination' => $error->handledDestination(),
                'last_seen_at' => $error->lastSeenAt(),
            ]);

        foreach ($error->hits() as $hit) {
            Hit::updateOrCreate([
                'error_uuid' => $error->id(),
                'timestamp' => $hit['timestamp'],
            ], [
                'uuid' => Str::uuid()->toString(),
                'data' => $hit['data'],
            ]);
        }
    }

    /** @var Error $error */
    public function delete($error)
    {
        $this->query()
            ->where('uuid', $error->id())
            ->delete();
    }

    public function query()
    {
        return ErrorModel::query()->with('hits');
    }

    public function make(): Error
    {
        return new Error;
    }
}
