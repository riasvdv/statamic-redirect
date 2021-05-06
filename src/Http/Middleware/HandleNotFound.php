<?php

namespace Rias\StatamicRedirect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Redirect;
use Rias\StatamicRedirect\Facades\Error as ErrorFacade;
use Rias\StatamicRedirect\Jobs\CleanErrorsJob;
use Statamic\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            $url = Str::start($request->getRequestUri(), '/');
            $logErrors = config('statamic.redirect.log_errors');

            if ($logErrors) {
                $error = $this->createError($request, $url);
            }

            CleanErrorsJob::dispatchIf(config('statamic.redirect.clean_errors_on_save'));

            $this->cachedRedirects = Cache::get('statamic.redirect.redirects', []);

            if (isset($this->cachedRedirects[$url])) {
                if ($logErrors) {
                    $this->markErrorHandled($error, $this->cachedRedirects[$url]['destination']);
                }

                if ((string) $this->cachedRedirects[$url]['type'] === (string) 410) {
                    abort(410);
                }

                return redirect(
                    $this->cachedRedirects[$url]['destination'],
                    $this->cachedRedirects[$url]['type'],
                );
            }

            if (! $redirect = \Rias\StatamicRedirect\Facades\Redirect::findByUrl($url)) {
                return $response;
            }

            $this->cacheNewRedirect($redirect, $url);

            if ($logErrors) {
                $this->markErrorHandled($error, $redirect->destination());
            }

            if ((string) $redirect->type() === (string) 410) {
                abort(410);
            }

            return redirect($redirect->destination(), $redirect->type());
        } catch (\Exception $e) {
            if ($e instanceof HttpException && $e->getStatusCode() === 410) {
                throw $e;
            }

            /*
             * If something goes wrong when logging the error
             * we don't want to crash the application, so
             * we just log the exception that occured.
             */
            logger($e);

            return $response;
        }
    }

    private function createError(Request $request, string $url): Error
    {
        $error = ErrorFacade::findByUrl($url);

        if (! $error) {
            $error = ErrorFacade::make()->url($url);
        }

        $error->addHit(now()->timestamp, [
            'userAgent' => $request->userAgent(),
            'ip' => $request->ip(),
            'referer' => $request->header('referer'),
        ]);
        $error->save();

        return $error;
    }

    private function markErrorHandled(Error $error, string $destination): void
    {
        $error->handled(true);
        $error->handledDestination($destination);
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
