<?php

namespace Rias\StatamicRedirect\Actions;

use Rias\StatamicRedirect\Contracts\Redirect;
use Statamic\Actions\Action;
use Statamic\Exceptions\FatalException;
use Statamic\Facades\File;
use Statamic\Support\Str;

class Export extends Action
{
    protected $fields = [
        'type' => [
            'label' => 'File type',
            'type' => 'select',
            'options' => [
                'csv' => 'CSV',
                'json' => 'JSON',
            ],
            'validate' => 'required',
        ]
    ];

    public function visibleTo($item)
    {
        return $item instanceof Redirect;
    }

    public function confirmationText()
    {
        return 'Are you sure you want to run this export?|Are you sure you want to export :count items?';
    }

    public function icon(): string
    {
        return 'download';
    }

    public function buttonText()
    {
        return 'Export';
    }

    public function authorize($user, $item)
    {
        return $user->can('view', Redirect::class);
    }

    public function download($items, $values)
    {
        $type = $values['type'];
        $exporter = 'Rias\StatamicRedirect\Exporters\\'.Str::studly($type).'Exporter';

        if (! class_exists($exporter)) {
            throw new FatalException("Exporter of type [$type] does not exist.");
        }

        $exporter = new $exporter($items);

        $content = $exporter->export();

        $path = storage_path('statamic/tmp/redirect/'.time().'.'.$type);

        File::put($path, $content);

        return $path;
    }
}
