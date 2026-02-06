<?php

namespace Rias\StatamicRedirect\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RedirectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id(),
            'enabled' => $this->resource->enabled(),
            'source' => $this->resource->source(),
            'destination' => $this->resource->destination(),
            'destination_type' => $this->resource->destination_type(),
            'destination_entry' => $this->resource->destination_entry(),
            'type' => $this->resource->type(),
            'match_type' => $this->resource->matchType(),
            'site' => $this->resource->site(),
            'order' => $this->resource->order(),
            'description' => $this->resource->description(),
            'last_used_at' => $this->resource->lastUsedAt(),
        ];
    }
}
