<?php

namespace Rias\StatamicRedirect\Exporters;

use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Forms\Exporters\Exporter;

class JsonExporter extends Exporter
{
    protected static string $title = 'Redirects';

    /**
     * Perform the export.
     *
     * @return string
     */
    public function export(): string
    {
        $submissions = Redirect::all()->map(function (\Rias\StatamicRedirect\Data\Redirect $redirect) {
            $redirectData = $redirect->fileData();

            if (! isset($redirectData['site'])) {
                $redirectData['site'] = $redirect->site()
                    ? $redirect->site()
                    : null;
            }

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
    public function contentType(): string
    {
        return 'application/json';
    }
}
