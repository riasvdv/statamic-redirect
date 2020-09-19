<?php

namespace Rias\StatamicRedirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rias\StatamicRedirect\Models\Error;
use Rias\StatamicRedirect\Models\Redirect;
use Statamic\Support\Str;

class HandleNotFound
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($response->status() !== 404) {
            return $response;
        }

        try {
            $error = Error::create(['url' => $request->path()]);
            $redirect = Redirect::findForUrl(Str::start($request->path(), '/'));

            if (! $redirect) {
                return $response;
            }

            $error->handled = true;
            $error->save();

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
