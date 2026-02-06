<?php

namespace Rias\StatamicRedirect\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect as RedirectFacade;
use Statamic\Console\RunsInPlease;

class GenerateVercelRedirects extends Command
{
    use RunsInPlease;

    protected $signature = 'redirect:generate-vercel-redirects';

    protected $description = 'Generate Vercel redirects in vercel.json';

    public function handle(): void
    {
        $redirects = RedirectFacade::all()->map(function (Redirect $redirect) {
            $statusCode = (int) $redirect->type();

            if ($statusCode === 410) {
                $statusCode = 301; // Vercel redirects don't support 410
            }

            $source = $redirect->source();
            $destination = $redirect->destination();

            if ($redirect->matchType() === MatchTypeEnum::REGEX) {
                $source = str_replace('(.*)', ':path*', $source);
                $destination = str_replace('$1', ':path*', $destination);
            }

            return [
                'source' => $source,
                'destination' => $destination,
                'statusCode' => $statusCode,
            ];
        })->values()->all();

        $path = base_path('vercel.json');

        $config = File::exists($path)
            ? json_decode(File::get($path), true) ?? []
            : [];

        if (File::exists($path) && ! $this->components->confirm('vercel.json already exists. Do you want to update the redirects?')) {
            return;
        }

        $config['redirects'] = $redirects;

        File::put($path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

        $this->components->success("Redirects generated in: {$path}");
    }
}
