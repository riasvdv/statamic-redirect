<?php

it('does not have duplicate route names', function () {
    // Regression test for https://github.com/riasvdv/statamic-redirect/issues/259
    // Previously, both POST and PATCH /{id} routes were named 'update',
    // causing a LogicException when running route:cache.

    $routes = app('router')->getRoutes();
    $names = collect($routes->getRoutesByName())
        ->keys()
        ->filter(fn (string $name) => str_starts_with($name, 'statamic.cp.redirect.'));

    expect($names)->not->toBeEmpty();
    expect($names->duplicates())->toBeEmpty();

    $updateRoute = collect($routes->getRoutes())
        ->first(fn ($route) => str_contains($route->getName() ?? '', 'redirect.redirects.update'));

    expect($updateRoute)->not->toBeNull();
    expect($updateRoute->methods())->toContain('POST');
    expect($updateRoute->methods())->toContain('PATCH');
});
