<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Facades\Redirect;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ImportRedirectsController
{
    public function index()
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects'), 401);

        return view('redirect::redirects.import');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects'), 401);

        $request->validate([
            'file' => ['required', 'file'],
            'delimiter' => ['required'],
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');
        $delimiter = request('delimiter', ',');

        $reader = SimpleExcelReader::create($file->getRealPath(), $file->extension())
            ->useDelimiter($delimiter);

        $reader->getRows()->each(function (array $data) {
            $redirect = Redirect::make()
                ->source($data['source'])
                ->destination($data['destination'])
                ->enabled(true)
                ->type($data['type'])
                ->matchType($data['match_type']);

            $redirect->save();
        });

        session()->flash('success', 'Redirects imported successfully');

        Cache::forget('statamic.redirect.redirects');

        return redirect()->action([RedirectController::class, 'index']);
    }
}
