<?php

namespace Rias\StatamicRedirect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Contracts\Redirect as RedirectContract;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Jobs\CleanErrorsJob;
use Statamic\Facades\Site;
use Statamic\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandleNotFound
{
    private array $cachedRedirects;

    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($response->getStatusCode() !== 404 || ! config('statamic.redirect.enable', true)) {
            return $response;
        }

        try {
            $site = Site::findByUrl($request->url()) ?? Site::default();
            $uri = $request->getRequestUri();

            // Remove site URI
            $siteUri = Str::after($site->url(), '/');
            if (! empty($siteUri) && str_contains($uri, $siteUri)) {
                $uri = Str::after($uri, $site->url());
            }
            // Make sure it starts with '/'
            $uri = Str::start($uri, '/');
            // Make sure we remove any trailing slash
            $uri = Str::chopEnd($uri, '/');

            $logErrors = config('statamic.redirect.log_errors', true);

            if ($logErrors) {
                $error = $this->createError($request, $uri);
                CleanErrorsJob::dispatchIf(config('statamic.redirect.clean_errors_on_save'));
            }

            $this->cachedRedirects = Cache::get('statamic.redirect.redirects', []);

            if (isset($this->cachedRedirects[$site->handle()][$uri])) {
                if ($logErrors) {
                    $this->markErrorHandled($error, $this->cachedRedirects[$site->handle()][$uri]['destination']);
                }

                if ((string) $this->cachedRedirects[$site->handle()][$uri]['type'] === '410') {
                    abort(410);
                }

                $destination = $this->cachedRedirects[$site->handle()][$uri]['destination'];

                return redirect(
                    $this->mergeQuery($request, $destination),
                    $this->cachedRedirects[$site->handle()][$uri]['type'],
                );
            }

            if (! $redirect = Redirect::findByUrl($site->handle(), $uri)) {
                return $response;
            }

            $this->cacheNewRedirect($site, $redirect, $uri);

            if ($logErrors) {
                $this->markErrorHandled($error, $redirect->destination());
            }

            if ((string) $redirect->type() === '410') {
                abort(410);
            }

            return redirect(
                $this->mergeQuery($request, $redirect->destination()),
                $redirect->type()
            );
        } catch (\Exception $e) {
            if ($e instanceof HttpException && $e->getStatusCode() === 410) {
                throw $e;
            }

            /*
             * If something goes wrong when logging the error
             * we don't want to crash the application, so
             * we just log the exception that occured.
             */
            report($e);

            return $response;
        }
    }

    private function createError(Request $request, string $url): Error
    {
        $error = Error::findByUrl($url);

        if (! $error) {
            $error = new Error(['url' => $url]);
        }

        $error->lastSeenAt = now()->timestamp;
        $error->save();

        $error->addHit(now()->timestamp, [
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
            'referer' => $request->header('referer'),
        ]);

        return $error;
    }

    private function markErrorHandled(Error $error, ?string $destination): void
    {
        $error->handled = true;
        $error->handledDestination = $destination;
        $error->save();
    }

    /**
     * @param  \Rias\StatamicRedirect\Data\Redirect  $redirect
     */
    private function cacheNewRedirect(\Statamic\Sites\Site $site, RedirectContract $redirect, string $url): void
    {
        $this->cachedRedirects[$site->handle()][$url] = [
            'destination' => $redirect->destination(),
            'type' => $redirect->type(),
        ];

        Cache::put('statamic.redirect.redirects', $this->cachedRedirects);
    }

    public function mergeQuery(Request $request, string $destination): string
    {
        if (! config('statamic.redirect.preserve_query_strings')) {
            return $destination;
        }

        if (! $request->getQueryString()) {
            return $destination;
        }

        $destination_parsed = parse_url($destination);
        $destination_query = [];

        if (isset($destination_parsed['query'])) {
            parse_str($destination_parsed['query'], $destination_query);
        }
        // in case of duplicate keys, the destination ones take precedence
        $query = array_merge($request->query(), $destination_query);

        ksort($query);

        if (count($query)) {
            // make sure to preserve fragment if specified in destination
            $fragment = isset($destination_parsed['fragment']) ? '#'.$destination_parsed['fragment'] : '';
            if (($urlBeforeFragment = strstr($destination, '#', true)) === false) {
                $urlBeforeFragment = $destination;
            }
            if (($urlBeforeQuery = strstr($urlBeforeFragment, '?', true)) === false) {
                $urlBeforeQuery = $destination;
            }

            $destination = $urlBeforeQuery.'?'.http_build_query($query).$fragment;
        }

        return $destination;
    }
}
