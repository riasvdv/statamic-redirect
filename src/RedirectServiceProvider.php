<?php

namespace Rias\StatamicRedirect;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Http\Filters\ErrorHandled;
use Rias\StatamicRedirect\Http\Filters\MatchType;
use Rias\StatamicRedirect\Http\Filters\RedirectSite;
use Rias\StatamicRedirect\Http\Filters\Type;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Listeners\CacheOldUri;
use Rias\StatamicRedirect\Listeners\CreateRedirect;
use Rias\StatamicRedirect\Stache\Redirects\RedirectStore;
use Rias\StatamicRedirect\UpdateScripts\AddHitsCount;
use Rias\StatamicRedirect\UpdateScripts\ClearErrors;
use Rias\StatamicRedirect\UpdateScripts\MoveRedirectsToDefaultSite;
use Rias\StatamicRedirect\UpdateScripts\RenameLocaleToSiteOnRedirectsTable;
use Rias\StatamicRedirect\Widgets\ErrorsLastDayWidget;
use Rias\StatamicRedirect\Widgets\ErrorsLastMonthWidget;
use Rias\StatamicRedirect\Widgets\ErrorsLastWeekWidget;
use Rias\StatamicRedirect\Widgets\ErrorsWidget;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntrySaving;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Git;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Stache\Stache;
use Statamic\Statamic;

class RedirectServiceProvider extends AddonServiceProvider
{
    protected $updateScripts = [
        AddHitsCount::class,
        ClearErrors::class,
        MoveRedirectsToDefaultSite::class,
        RenameLocaleToSiteOnRedirectsTable::class,
    ];

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

    protected $widgets = [
        ErrorsWidget::class,
        ErrorsLastMonthWidget::class,
        ErrorsLastWeekWidget::class,
        ErrorsLastDayWidget::class,
    ];

    public function register()
    {
        $this->registerAddonConfig();

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

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateRedirectTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_redirect_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_redirect_tables.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateEloquentRedirectTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_eloquent_redirect_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_eloquent_redirect_table.php'),
                ], 'redirect-eloquent-migrations');
            }
        }

        Statamic::booted(function () {
            $router = $this->app->make(Router::class);
            $router->prependMiddlewareToGroup('statamic.web', HandleNotFound::class);
            $router->prependMiddlewareToGroup('web', HandleNotFound::class);

            ErrorHandled::register();
            MatchType::register();
            Type::register();
            RedirectSite::register();

            if (config('statamic.git.enabled')) {
                Git::listen(RedirectSaved::class);
            }

            $this
                ->bootAddonViews()
                ->bootAddonNav()
                ->bootStores()
                ->bootDatabase()
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
        $redirectStore = new RedirectStore();
        $redirectStore->directory(config('statamic.redirect.redirect_store', base_path('content/redirects')));
        app(Stache::class)->registerStore($redirectStore);

        return $this;
    }

    protected function bootDatabase()
    {
        if (! config('statamic.redirect.log_errors')) {
            return $this;
        }

        File::ensureDirectoryExists(storage_path('redirect'));

        $sqlitePath = storage_path('redirect/errors.sqlite');

        if (! file_exists($sqlitePath)) {
            File::put($sqlitePath, '');

            $gitIgnorePath = storage_path('redirect/.gitignore');
            if (! file_exists($gitIgnorePath)) {
                File::put($gitIgnorePath, "*\n!.gitignore");
            }
        }

        app('config')->set('database.connections.redirect', [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
        ]);

        if (! Schema::connection(config('statamic.redirect.connection', 'redirect'))->hasTable('errors')) {
            $defaultConnection = DB::getDefaultConnection();

            DB::setDefaultConnection(config('statamic.redirect.connection', 'redirect'));

            require_once(__DIR__ . '/../database/migrations/create_redirect_tables.php.stub');

            (new \CreateRedirectTables())->up();

            DB::setDefaultConnection($defaultConnection);
        }

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
                $permission
                    ->label('View Redirects')
                    ->children([
                        Permission::make('edit redirects')
                            ->label('Edit Redirects')
                            ->children([
                                Permission::make('create redirects')->label('Create Redirects'),
                                Permission::make('delete redirects')->label('Delete Redirects'),
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
