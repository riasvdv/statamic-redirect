<?php

namespace Rias\StatamicRedirect\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Rias\StatamicRedirect\Facades\Redirect;
use Spatie\SimpleExcel\SimpleExcelReader;
use Statamic\Facades\Site;
use Statamic\Facades\User;

class ImportRedirectsController
{
    public function index()
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

        return view('redirect::redirects.import');
    }

    public function store(Request $request)
    {
        $user = User::fromUser(auth()->user());

        abort_unless($user->isSuper() || $user->hasPermission('create redirects'), 401);

        $request->validate([
            'file' => ['required', 'file'],
            'delimiter' => ['required'],
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');
        $delimiter = $request->input('delimiter', ',');

        $extension = with($file->extension(), fn ($ext) => $ext === 'txt' ? 'csv' : $ext);

        $reader = SimpleExcelReader::create($file->getRealPath(), $extension)
            ->useDelimiter($delimiter);

        $skipped = 0;
        $reader->getRows()->each(function (array $data) use (&$skipped) {
            if (! $data['source'] || ! $data['type'] || ! $data['match_type'] || (! $data['destination'] && ($data['type'] != 410))) {
                $skipped++;

                return;
            }

            if (Redirect::query()->where('source', $data['source'])->where('site', $data['site'] ?? Site::current()->handle())->count() > 0) {
                $skipped++;

                return;
            }

            /** @var Redirect $redirect */
            $redirect = Redirect::make()
                ->source($data['source'])
                ->destination($data['destination'])
                ->enabled(true)
                ->type((int) $data['type'])
                ->matchType($data['match_type']);

            if (isset($data['site'])) {
                $redirect->site($data['site']);
            }

            $redirect->save();
        });

        $message = 'Redirects imported successfully.';

        if ($skipped > 0) {
            $message .= " {$skipped} " . Str::plural('row', $skipped) . " skipped due to invalid data.";
        }

        session()->flash('success', $message);

        Cache::forget('statamic.redirect.redirects');

        return redirect()->action([RedirectController::class, 'index']);
    }
}
