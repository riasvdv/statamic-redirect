<?php

namespace Rias\StatamicRedirect\Exporters;

use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Forms\Exporters\AbstractExporter;

class JsonExporter extends AbstractExporter
{
    /**
     * Perform the export.
     *
     * @return string
     */
    public function export()
    {
        $submissions = Redirect::all()->map(function (Redirect $redirect) {
            $redirectData = $redirect->fileData();
            $redirectData['site'] = $redirect->site()->handle;

            unset($redirectData['id']);

            return $redirectData;
        })->toArray();

        return json_encode($submissions);
    }

    /**
     * Get the content type.
     *
     * @return string
     */
    public function contentType()
    {
        return 'application/json';
    }
}
