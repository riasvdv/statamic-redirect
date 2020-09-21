<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Statamic\CP\Column;
use Statamic\CP\Columns;

class RedirectsCollection extends ResourceCollection
{
    public $collects = ListedRedirect::class;

    protected $columnPreferenceKey;

    public function columnPreferenceKey($key)
    {
        $this->columnPreferenceKey = $key;

        return $this;
    }

    private function setColumns()
    {
        $columns = new Columns([
            Column::make('enabled')->label('Enabled'),
            Column::make('source')->label('Source'),
            Column::make('destination')->label('Destination'),
            Column::make('type')->label('Type'),
            Column::make('match_type')->label('Match Type'),
        ]);

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
                $redirect
                    ->columns($this->columns);
            }),

            'meta' => [
                'columns' => $this->columns,
            ],
        ];
    }
}
