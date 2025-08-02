<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
            if (! $data['source']) {
                Log::error('Redirect has no source', $data);
                $skipped++;

                return;
            }

            if (! $data['type']) {
                Log::error('Redirect has no type', $data);
                $skipped++;

                return;
            }

            if (! $data['match_type']) {
                Log::error('Redirect has no match_type', $data);
                $skipped++;

                return;
            }

            if ((! $data['destination'] && ($data['type'] != 410))) {
                Log::error('Redirect has no destination, it is required when type is not 410', $data);
                $skipped++;

                return;
            }

            $siteHandle = ! empty($data['site'])
                ? $data['site']
                : Site::current()->handle();

            $redirect = Redirect::query()
                ->where('source', $data['source'])
                ->where('site', $siteHandle)
                ->first();

            if (! $redirect) {
                $redirect = Redirect::make()->source($data['source']);
            }

            /** @var \Rias\StatamicRedirect\Data\Redirect $redirect */
            $redirect
                ->destination($data['destination'] ?? null)
                ->enabled($data['enabled'] ?? true)
                ->type((int) $data['type'])
                ->site($siteHandle)
                ->matchType($data['match_type']);

            $redirect->save();
        });

        $message = 'Redirects imported successfully.';

        if ($skipped > 0) {
            $message .= " {$skipped} ".Str::plural('row', $skipped).' skipped due to invalid data. You can find more info in the Laravel log.';
        }

        session()->flash('success', $message);

        Cache::forget('statamic.redirect.redirects');

        return redirect()->action([RedirectController::class, 'index']);
    }
}
