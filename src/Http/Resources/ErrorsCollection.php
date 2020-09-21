<?php

namespace Rias\StatamicRedirect\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Statamic\CP\Column;
use Statamic\CP\Columns;

class ErrorsCollection extends ResourceCollection
{
    public $collects = ListedError::class;

    protected $columnPreferenceKey;

    public function columnPreferenceKey($key)
    {
        $this->columnPreferenceKey = $key;

        return $this;
    }

    private function setColumns()
    {
        $columns = new Columns([
            Column::make('url')->label('Path'),
            Column::make('hitsCount')->label('Hits'),
            Column::make('latest')->label('Latest error'),
            Column::make('handled')->label('Handled'),
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
            'data' => $this->collection->each(function ($error) {
                $error
                    ->columns($this->columns);
            }),

            'meta' => [
                'columns' => $this->columns,
            ],
        ];
    }
}
