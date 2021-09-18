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
        $columns = [
            Column::make('url')->label('Path'),
        ];

        if (config('statamic.redirect.log_hits', true)) {
            $columns[] = Column::make('hitsCount')->label('Hits');
        }

        $columns = array_merge($columns, [
            Column::make('latest')->label('Latest error'),
            Column::make('handled')->label('Handled'),
            Column::make('handledDestination')->label('Destination'),
        ]);

        $columns = new Columns($columns);

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
                $error->columns($this->columns);
            }),

            'meta' => [
                'columns' => $this->columns,
            ],
        ];
    }
}
