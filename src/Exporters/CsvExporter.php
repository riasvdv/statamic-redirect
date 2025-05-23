<?php

namespace Rias\StatamicRedirect\Exporters;

use League\Csv\Writer;
use Rias\StatamicRedirect\Facades\Redirect;
use SplTempFileObject;
use Statamic\Forms\Exporters\Exporter;
use Statamic\Support\Arr;

class CsvExporter extends Exporter
{
    protected static string $title = 'Redirects';

    /**
     * @var Writer
     */
    private $writer;

    /**
     * Create a new CsvExporter.
     */
    public function __construct()
    {
        $this->writer = Writer::createFromFileObject(new SplTempFileObject);
    }

    /**
     * Perform the export.
     *
     * @return string
     */
    public function export(): string
    {
        $this->insertHeaders();

        $this->insertData();

        return (string) $this->writer;
    }

    /**
     * Insert the headers into the CSV.
     */
    private function insertHeaders()
    {
        $headers = array_keys(Redirect::all()->first()->fileData());

        $headers = Arr::except($headers, array_search('id', $headers, true));

        if (! in_array('site', $headers)) {
            $headers[] = 'site';
        }

        $this->writer->insertOne($headers);
    }

    /**
     * Insert the submission data into the CSV.
     */
    private function insertData()
    {
        $data = Redirect::all()->map(function (\Rias\StatamicRedirect\Data\Redirect $redirect) {
            $redirectData = $redirect->fileData();

            if (! isset($redirectData['site'])) {
                $redirectData['site'] = $redirect->site()
                    ? $redirect->site()
                    : null;
            }

            return Arr::except($redirectData, ['id']);
        })->all();

        $this->writer->insertAll($data);
    }

    public function contentType(): string
    {
        return 'text/csv';
    }
}
