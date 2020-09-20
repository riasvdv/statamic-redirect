<?php

namespace Rias\StatamicRedirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\Repositories\ErrorRepository;
use Rias\StatamicRedirect\Repositories\RedirectRepository;
use Statamic\Support\Str;

class HandleNotFound
{
    /** @var \Rias\StatamicRedirect\Repositories\ErrorRepository */
    private $errorRepository;

    /** @var \Rias\StatamicRedirect\Repositories\RedirectRepository */
    private $redirectRepository;

    /** @var array */
    private $cachedRedirects;

    public function __construct(ErrorRepository $errorRepository, RedirectRepository $redirectRepository)
    {
        $this->errorRepository = $errorRepository;
        $this->redirectRepository = $redirectRepository;
    }

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

            if (! $redirect = $this->redirectRepository->findForUrl($url)) {
                return $response;
            }

            $this->cacheNewRedirect($redirect, $url);
            $this->markErrorHandled($error);

            return redirect($redirect->destination, $redirect->type);
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
        $error = new Error([
            'id' => $this->errorRepository->nextId(),
            'url' => $url,
            'date' => now()->timestamp,
        ]);

        $this->errorRepository->save($error);

        return $error;
    }

    private function markErrorHandled(Error $error): void
    {
        $error->handled = true;
        $this->errorRepository->save($error);
    }

    /**
     * @param \Rias\StatamicRedirect\DataTransferObjects\Redirect $redirect
     * @param string $url
     */
    private function cacheNewRedirect(Redirect $redirect, string $url): void
    {
        $this->cachedRedirects[$url] = [
            'destination' => $redirect->destination,
            'type' => $redirect->type,
        ];

        Cache::put('statamic.redirect.redirects', $this->cachedRedirects);
    }
}
