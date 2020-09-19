<?php

namespace Rias\StatamicRedirect;

use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Listeners\CacheOldUri;
use Rias\StatamicRedirect\Listeners\CreateRedirect;
use Rias\StatamicRedirect\Middleware\HandleNotFound;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntrySaving;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class RedirectServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $commands = [
        CleanErrorsCommand::class,
    ];

    protected $listen = [
        EntrySaving::class => [
            CacheOldUri::class,
        ],
        EntrySaved::class => [
            CreateRedirect::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            /** @var \Illuminate\Routing\Router $router */
            $router = app('router');
            $router->prependMiddlewareToGroup('statamic.web', HandleNotFound::class);
        });

        $this
            ->bootAddonViews()
            ->bootAddonNav()
            ->bootAddonConfig();
    }

    protected function bootAddonViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redirect');

        return $this;
    }

    protected function bootAddonNav()
    {
        Nav::extend(function ($nav) {
            $nav->tools('Redirect')
                ->route('redirect.index')
                ->icon('git')
                ->active('redirect')
                ->children([
                    'Dashboard' => cp_route('redirect.index'),
                    'Redirects' => cp_route('redirect.redirects.index'),
                ]);
        });

        return $this;
    }

    protected function bootAddonConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/redirect.php', 'statamic.redirect');

        $this->publishes([
            __DIR__.'/../config/redirect.php' => config_path('statamic/redirect.php'),
        ], 'redirect-config');

        return $this;
    }

    protected function schedule($schedule)
    {
        $schedule->command(CleanErrorsCommand::class)->daily();
    }
}
