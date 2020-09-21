<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListedRedirect extends JsonResource
{
    protected $columns;

    public function columns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function toArray($request)
    {
        /** @var \Rias\StatamicRedirect\Data\Redirect $redirect */
        $redirect = $this->resource;

        return [
            'id' => $redirect->id(),
            'enabled' => $redirect->enabled(),
            'source' => $redirect->source(),
            'destination' => $redirect->destination(),
            'type' => $redirect->type(),
            'match_type' => $redirect->matchType(),
        ];
    }
}
