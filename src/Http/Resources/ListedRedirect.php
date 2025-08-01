<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Statamic\Facades\User;

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
            'type' => [$redirect->type()],
            'match_type' => [$redirect->matchType()],
            'site' => $redirect->site(),
            'order' => $redirect->order(),
            'description' => $redirect->description(),
            'delete_url' => cp_route('redirect.redirects.delete', $redirect->id()),
            'editable' => User::fromUser(auth()->user())->can('edit', $redirect),
            'edit_url' => cp_route('redirect.redirects.edit', $redirect->id()),
        ];
    }
}
