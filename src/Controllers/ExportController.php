<?php

namespace Rias\StatamicRedirect\Controllers;

use Statamic\Exceptions\FatalException;
use Statamic\Facades\File;
use Statamic\Support\Str;

class ExportController
{
    public function __invoke($type)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('view redirects'), 401);

        $exporter = 'Rias\StatamicRedirect\Exporters\\'.Str::studly($type).'Exporter';

        if (! class_exists($exporter)) {
            throw new FatalException("Exporter of type [$type] does not exist.");
        }

        $exporter = new $exporter;

        $content = $exporter->export();

        if (request()->has('download')) {
            $path = storage_path('statamic/tmp/redirect/'.time().'.'.$type);
            File::put($path, $content);
            $response = response()->download($path)->deleteFileAfterSend(true);
        } else {
            $response = response($content)->header('Content-Type', $exporter->contentType());
        }

        return $response;
    }
}
