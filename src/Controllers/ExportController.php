<?php

namespace Rias\StatamicRedirect\Controllers;

use Statamic\Exceptions\FatalException;
use Statamic\Facades\File;
use Statamic\Facades\User;
use Statamic\Support\Str;

class ExportController
{
    public function __invoke($type)
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('view redirects'), 401);

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
