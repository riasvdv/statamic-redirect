<?php

namespace Rias\StatamicRedirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\Repositories\ErrorRepository;
use Rias\StatamicRedirect\Repositories\RedirectRepository;
use Statamic\Support\Str;

class HandleNotFound
{
    /** @var \Rias\StatamicRedirect\Repositories\ErrorRepository */
    private $errorRepository;

    /** @var \Rias\StatamicRedirect\Repositories\RedirectRepository */
    private $redirectRepository;

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
            $error = new Error([
                'id' => $this->errorRepository->nextId(),
                'url' => $request->path(),
                'date' => now()->timestamp,
            ]);

            $this->errorRepository->save($error);

            $redirect = $this->redirectRepository->findForUrl(Str::start($request->path(), '/'));

            if (! $redirect) {
                return $response;
            }

            $error->handled = true;
            $this->errorRepository->save($error);

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
}
