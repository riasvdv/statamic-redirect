<?php

namespace Rias\StatamicRedirect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Facades\Error as ErrorFacade;
use Statamic\Support\Str;

class HandleNotFound
{
    /** @var array */
    private $cachedRedirects;

    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($response->status() !== 404) {
            return $response;
        }

        try {
            $url = Str::start($request->path(), '/');
            $error = $this->createError($url);

            $this->cachedRedirects = Cache::get('statamic.redirect.redirects', []);

            if (isset($this->cachedRedirects[$url])) {
                $this->markErrorHandled($error);

                return redirect(
                    $this->cachedRedirects[$url]['destination'],
                    $this->cachedRedirects[$url]['type'],
                );
            }

            if (! $redirect = \Rias\StatamicRedirect\Facades\Redirect::findByUrl($url)) {
                return $response;
            }

            $this->cacheNewRedirect($redirect, $url);
            $this->markErrorHandled($error);

            return redirect($redirect->destination(), $redirect->type());
        } catch (\Exception $e) {
            /*
             * If something goes wrong when logging the error
             * we don't want to crash the application, so
             * we just log the exception that occured.
             */
            logger($e);

            return $response;
        }
    }

    private function createError(string $url): Error
    {
        $error = ErrorFacade::findByUrl($url);

        if (! $error) {
            $error = ErrorFacade::make()->url($url);
        }

        $error->addHit(now()->timestamp);
        $error->save();

        return $error;
    }

    private function markErrorHandled(Error $error): void
    {
        $error->handled(true);
        $error->save();
    }

    /**
     * @param \Rias\StatamicRedirect\Data\Redirect $redirect
     * @param string $url
     */
    private function cacheNewRedirect(Redirect $redirect, string $url): void
    {
        $this->cachedRedirects[$url] = [
            'destination' => $redirect->destination(),
            'type' => $redirect->type(),
        ];

        Cache::put('statamic.redirect.redirects', $this->cachedRedirects);
    }
}
