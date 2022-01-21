<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'url' => $this->url,
            'handled' => $this->handled,
            'handledDestination' => $this->handledDestination,
            'lastSeenAt' => $this->lastSeenAt,
            'hitsCount' => $this->hitsCount,
        ];
    }
}
