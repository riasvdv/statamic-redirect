<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Statamic\Facades\Action;
use Statamic\Facades\User;

class ListedError extends JsonResource
{
    protected $columns;

    public function columns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function toArray($request)
    {
        /** @var \Rias\StatamicRedirect\Data\Error $error */
        $error = $this->resource;

        return [
            'id' => $error->id(),
            'url' => $error->url(),
            'handled' => $error->handled(),
            'latest' => $error->latest(),
            'hits' => $error->hits(),
            'hitsCount' => count($error->hits()),
        ];
    }
}
