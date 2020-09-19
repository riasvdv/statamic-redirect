<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Support\Collection;
use Rias\StatamicRedirect\Models\Error;

class ErrorController
{
    public function index()
    {
        return Error::all()->groupBy(function (Error $error) {
            return $error->url;
        })->map(function ($errors, $url) {
            return [
                'hits' => $errors->count(),
                'url' => $url,
                'latest' => $errors->last()->date,
                'handled' => $errors->last()->handled,
            ];
        })->sortByDesc('latest')->values()->toJson();
    }

    public function show()
    {

    }
}
