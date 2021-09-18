<?php

namespace Rias\StatamicRedirect;

use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Contracts\ErrorRepository;
use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Rias\StatamicRedirect\Http\Filters\ErrorFields;
use Rias\StatamicRedirect\Http\Filters\ErrorHandled;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Listeners\CacheOldUri;
use Rias\StatamicRedirect\Listeners\CreateRedirect;
use Rias\StatamicRedirect\Stache\Errors\ErrorStore;
use Rias\StatamicRedirect\Stache\Redirects\RedirectStore;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntrySaving;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Folder;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Stache\Stache;
use Statamic\Statamic;

class RedirectServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $listen = [
        EntrySaving::class => [
            CacheOldUri::class,
        ],
        EntrySaved::class => [
            CreateRedirect::class,
        ],
    ];

    public function register()
    {
        $this->registerAddonConfig();

        $this->app->singleton(ErrorRepository::class, function () {
            $class = config('statamic.redirect.error_repository');

            return new $class($this->app['stache']);
        });

        $this->app->singleton(RedirectRepository::class, function () {
            $class = config('statamic.redirect.redirect_repository');

            return new $class($this->app['stache']);
        });
    }

    public function boot()
    {
        parent::boot();

        $this->commands([
            CleanErrorsCommand::class,
        ]);

        /** Remove old format of errors @todo: Remove in future version */
        Folder::delete(storage_path('redirect/errors/2020'));

        Statamic::booted(function () {
            app('router')->prependMiddlewareToGroup('statamic.web', HandleNotFound::class);

            ErrorHandled::register();
            ErrorFields::register();

            $this
                ->bootAddonViews()
                ->bootAddonNav()
                ->bootStores()
                ->bootPermissions();
        });
    }

    protected function bootAddonViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redirect');

        return $this;
    }

    protected function bootAddonNav()
    {
        Nav::extend(function ($nav) {
            $items = [];

            if (config('statamic.redirect.log_errors')) {
                $items['Dashboard'] = cp_route('redirect.index');
            }

            $items['Redirects'] = cp_route('redirect.redirects.index');

            $nav->tools('Redirect')
                ->route(
                    config('statamic.redirect.log_errors')
                    ? 'redirect.index'
                    : 'redirect.redirects.index'
                )
                ->icon('git')
                ->active('redirect')
                ->can('view redirects')
                ->children($items);
        });

        return $this;
    }

    protected function bootStores()
    {
        $errorStore = new ErrorStore();
        $errorStore->directory(storage_path('redirect/errors'));
        app(Stache::class)->registerStore($errorStore);

        $redirectStore = new RedirectStore();
        $redirectStore->directory(base_path('content/redirects'));
        app(Stache::class)->registerStore($redirectStore);

        return $this;
    }

    protected function registerAddonConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/redirect.php', 'statamic.redirect');

        $this->publishes([
            __DIR__.'/../config/redirect.php' => config_path('statamic/redirect.php'),
        ], 'statamic-redirect-config');

        return $this;
    }

    protected function bootPermissions()
    {
        Permission::group('redirect', 'Redirects', function () {
            Permission::register('view redirects', function ($permission) {
                $permission->children([
                    Permission::make('edit redirects')->children([
                        Permission::make('create redirects'),
                        Permission::make('delete redirects'),
                    ]),
                ]);
            });
        });

        return $this;
    }

    protected function schedule($schedule)
    {
        $schedule->command(CleanErrorsCommand::class)->daily();
    }
}
