<?php

namespace Rias\StatamicRedirect\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Resources\Api\RedirectResource;

class RedirectsController extends Controller
{
    public function index(Request $request)
    {
        $query = Redirect::query();

        if ($request->has('site')) {
            $query->where('site', $request->get('site'));
        }

        if ($request->has('enabled')) {
            $query->where('enabled', filter_var($request->get('enabled'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('match_type')) {
            $query->where('match_type', $request->get('match_type'));
        }

        if ($request->has('type')) {
            $query->where('type', (int) $request->get('type'));
        }

        $sortField = $request->get('sort', 'order');
        $sortDirection = $request->get('order', 'asc');
        $query->orderBy($sortField, $sortDirection);

        if ($request->has('limit')) {
            $limit = (int) $request->get('limit', 25);
            $paginated = $query->paginate($limit);

            return RedirectResource::collection($paginated);
        }

        return RedirectResource::collection($query->get());
    }

    public function show(string $redirect)
    {
        $redirect = Redirect::find($redirect);

        if (! $redirect) {
            abort(404);
        }

        return new RedirectResource($redirect);
    }
}
