<?php

namespace Rias\StatamicRedirect\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect as RedirectFacade;
use Statamic\Console\RunsInPlease;

class GenerateCloudflareRedirects extends Command
{
    use RunsInPlease;

    protected $signature = 'redirect:generate-cloudflare-redirects';

    protected $description = 'Generate a Cloudflare redirects file';

    public function handle(): void
    {
        $lines = RedirectFacade::all()->map(function (Redirect $redirect) {
            $type = $redirect->type();

            if ((string) $type === '410') {
                $type = 301; // Cloudflare doesn't support 410
            }

            if ($redirect->matchType() === MatchTypeEnum::EXACT) {
                return "{$redirect->source()} {$redirect->destination()} {$type}";
            }

            $source = $redirect->source();
            $destination = $redirect->destination();

            $source = str_replace('(.*)', '*', $source);
            $destination = str_replace('$1', ':splat', $destination);

            return "{$source} {$destination} {$type}";
        });

        File::put(base_path('_redirects'), $lines->implode("\n"));

        $this->components->success("Redirects file generated in: " . base_path('_redirects'));
    }
}
