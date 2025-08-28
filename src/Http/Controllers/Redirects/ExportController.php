<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Statamic\Exceptions\FatalException;
use Statamic\Facades\File;
use Statamic\Support\Str;

class ExportController
{
    use AuthorizesRequests;

    public function __invoke($type)
    {
        $this->authorize('view', RedirectContract::class);

        $exporter = 'Rias\StatamicRedirect\Exporters\\'.Str::studly($type).'Exporter';

        if (! class_exists($exporter)) {
            throw new FatalException("Exporter of type [$type] does not exist.");
        }

        $exporter = new $exporter;

        $content = $exporter->export();

        if (request()->has('download')) {
            $path = storage_path('statamic/tmp/redirect/'.time().'.'.$type);

            File::put($path, $content);

            return response()->download($path)->deleteFileAfterSend();
        }

        return response($content)->header('Content-Type', $exporter->contentType());
    }
}
