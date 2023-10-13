<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Statamic\CP\Column;
use Statamic\CP\Columns;
use Statamic\Fields\Blueprint;
use Statamic\Http\Resources\CP\Concerns\HasRequestedColumns;

class RedirectsCollection extends ResourceCollection
{
    use HasRequestedColumns;

    public $collects = ListedRedirect::class;

    protected $columnPreferenceKey;

    public function columnPreferenceKey($key)
    {
        $this->columnPreferenceKey = $key;

        return $this;
    }

    private function setColumns()
    {
        $blueprint = (new RedirectBlueprint())();
        $columns = $blueprint->columns();

        if ($key = $this->columnPreferenceKey) {
            $columns->setPreferred($key);
        }

        $this->columns = $columns->rejectUnlisted()->values();
    }

    public function toArray($request)
    {
        $this->setColumns();

        return [
            'data' => $this->collection->each(function ($redirect) {
                $redirect->columns($this->requestedColumns());
            }),

            'meta' => [
                'columns' => $this->visibleColumns(),
            ],
        ];
    }
}
