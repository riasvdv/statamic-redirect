<?php

namespace Rias\StatamicRedirect\Exporters;

use League\Csv\Writer;
use Rias\StatamicRedirect\Blueprints\RedirectBlueprint;
use Rias\StatamicRedirect\Facades\Redirect;
use SplTempFileObject;
use Statamic\Forms\Exporters\AbstractExporter;
use function Symfony\Component\String\s;

class CsvExporter extends AbstractExporter
{
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
    public function export()
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

        unset($headers[array_search('id', $headers)]);

        $this->writer->insertOne($headers);
    }

    /**
     * Insert the submission data into the CSV.
     */
    private function insertData()
    {
        $data = Redirect::all()->map(function (\Rias\StatamicRedirect\Data\Redirect $redirect) {
            $redirect = $redirect->fileData();

            unset($redirect['id']);

            return $redirect;
        })->all();

        $this->writer->insertAll($data);
    }
}
