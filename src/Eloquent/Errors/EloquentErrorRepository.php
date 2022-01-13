<?php

namespace Rias\StatamicRedirect\Eloquent\Errors;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rias\StatamicRedirect\Contracts\ErrorRepository as RepositoryContract;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Eloquent\Errors\Error as ErrorModel;
use Statamic\Data\DataCollection;
use Statamic\Facades\YAML;

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

    /** @var Error */
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
                'hits_count' => $error->hitsCount(),
            ]);
    }

    /** @var Error */
    public function delete($error)
    {
        Hit::where('error_uuid', $error->id())->delete();

        $this->query()
            ->where('uuid', $error->id())
            ->delete();
    }

    public function hits($error)
    {
        return Hit::where('error_uuid', $error->id())->get()->map(function (Hit $hit) {
            return [
                'timestamp' => $hit->timestamp,
                'data' => $hit->data,
            ];
        })->toArray();
    }

    public function setHits($error, array $newHits)
    {
        $error->save();

        Hit::where('error_uuid', $error->id())->delete();

        $newHits = array_map(function ($newHit) use ($error) {
            $newHit['uuid'] = $newHit['uuid'] ?? Str::uuid();
            $newHit['data'] = json_encode($newHit['data']);
            $newHit['error_uuid'] = $error->id();

            return $newHit;
        }, $newHits);

        Hit::insert($newHits);
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
