<?php

namespace Rias\StatamicRedirect\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Facades\Redirect as RedirectFacade;
use Statamic\Console\RunsInPlease;

class GenerateNetlifyRedirects extends Command
{
    use RunsInPlease;

    protected $signature = 'redirect:generate-netlify-redirects';

    protected $description = 'Generate a Netlify redirects file';

    public function handle(): void
    {
        $lines = RedirectFacade::all()->map(function (Redirect $redirect) {
            $type = $redirect->type();

            if ((string) $type === '410') {
                $type = 301; // Netlify doesn't support 410
            }

            return <<<txt
            [[redirects]]
            from = "{$redirect->source()}"
            to = "{$redirect->destination()}"
            status = {$type}

            txt;
        });

        if (File::exists(base_path('netlify.toml')) && ! $this->components->confirm('netlify.toml already exists. Do you want to overwrite it?')) {
            return;
        }

        File::put(base_path('netlify.toml'), $lines->implode("\n"));

        $this->components->success('Redirects file generated in: '.base_path('netlify.toml'));
    }
}
