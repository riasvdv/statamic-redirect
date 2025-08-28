<?php

namespace Rias\StatamicRedirect\Exporters;

use Illuminate\Support\Collection;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Forms\Exporters\Exporter;

class JsonExporter extends Exporter
{
    protected static string $title = 'Redirects';

    public function __construct(
        /** @var Collection<\Rias\StatamicRedirect\Data\Redirect> $items */
        private ?Collection $items = null
    ) {
        $this->items = $items ?? Redirect::all();
    }

    /**
     * Perform the export.
     */
    public function export(): string
    {
        $submissions = $this->items->map(function (\Rias\StatamicRedirect\Data\Redirect $redirect) {
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
     */
    public function contentType(): string
    {
        return 'application/json';
    }
}
