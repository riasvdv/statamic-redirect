<?php

namespace Rias\StatamicRedirect\Http\Controllers\Redirects;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Facades\Redirect;
use Spatie\SimpleExcel\SimpleExcelReader;
use Statamic\Facades\Site;

class ImportRedirectsController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('create', RedirectContract::class);

        return Inertia::render('redirect::Redirects/Import', [
            'importUrl' => cp_route('redirect.redirects.handleImport'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', RedirectContract::class);

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
            if (! isset($data['source'])) {
                Log::error('Redirect has no source', $data);
                $skipped++;

                return;
            }

            if (! isset($data['type'])) {
                Log::error('Redirect has no type', $data);
                $skipped++;

                return;
            }

            if (! isset($data['match_type'])) {
                Log::error('Redirect has no match_type', $data);
                $skipped++;

                return;
            }

            if ((! isset($data['destination']) && (($data['type'] ?? null) != 410))) {
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
