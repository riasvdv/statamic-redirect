<?php

namespace Rias\StatamicRedirect;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Commands\CleanErrorsCommand;
use Rias\StatamicRedirect\Contracts\RedirectRepository;
use Rias\StatamicRedirect\Eloquent\Redirects\RedirectRepository as EloquentRedirectRepository;
use Rias\StatamicRedirect\Events\RedirectSaved;
use Rias\StatamicRedirect\Http\Filters\ErrorHandled;
use Rias\StatamicRedirect\Http\Filters\MatchType;
use Rias\StatamicRedirect\Http\Filters\RedirectSite;
use Rias\StatamicRedirect\Http\Filters\Type;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Listeners\CacheOldUri;
use Rias\StatamicRedirect\Listeners\CreateRedirect;
use Rias\StatamicRedirect\Stache\Redirects\RedirectRepository as StacheRedirectRepository;
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
            $class = $this->getRedirectRepository();

            return new $class($this->app['stache']);
        });
    }

    public function boot()
    {
        parent::boot();

        $this->commands([
            CleanErrorsCommand::class,
        ]);

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

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../database/migrations/create_redirect_error_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_redirect_error_tables.php'),
        ], 'statamic-redirect-error-migrations');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_redirect_redirects_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_redirect_redirects_table.php'),
        ], 'statamic-redirect-redirect-migrations');
    }

    protected function getRedirectRepository()
    {
        if (config('statamic.redirect.redirect_repository') !== null) {
            return config('statamic.redirect.redirect_repository');
        }

        if (config('statamic.redirect.redirect_connection') !== 'stache') {
            return EloquentRedirectRepository::class;
        }

        return StacheRedirectRepository::class;
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

        $sqlitePath = storage_path('redirect/redirect.sqlite');
        $this->ensureDatabaseExists($sqlitePath);

        app('config')->set('database.connections.redirect-sqlite', [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
        ]);

        $this->bootDatabaseForErrors();
        $this->bootDatabaseForRedirects();

        return $this;
    }

    protected function ensureDatabaseExists($sqlitePath)
    {
        $oldSqlitePath = storage_path('redirect/errors.sqlite');

        if (! file_exists($sqlitePath) && file_exists($oldSqlitePath)) {
            File::move($oldSqlitePath, $sqlitePath);

            return;
        }

        if (! file_exists($sqlitePath)) {
            File::put($sqlitePath, '');

            $gitIgnorePath = storage_path('redirect/.gitignore');
            if (! file_exists($gitIgnorePath)) {
                File::put($gitIgnorePath, "*\n!.gitignore");
            }
        }
    }

    protected function bootDatabaseForErrors()
    {
        if(
            config('statamic.redirect.error_connection', 'redirect-sqlite') !== 'redirect-sqlite' &&
            ! $this->generalConnectionIsBuiltinSqlite()
        ) {
            return;
        }

        if (Schema::connection('redirect-sqlite')->hasTable('errors')) {
            return;
        }

        $defaultConnection = DB::getDefaultConnection();
        DB::setDefaultConnection('redirect-sqlite');
        require_once(__DIR__ . '/../database/migrations/create_redirect_error_tables.php.stub');
        (new \CreateRedirectErrorTables())->up();
        DB::setDefaultConnection($defaultConnection);
    }

    protected function bootDatabaseForRedirects()
    {
        if(
            config('statamic.redirect.redirect_connection', 'stache') !== 'redirect-sqlite' &&
            ! $this->generalConnectionIsBuiltinSqlite()
        ) {
            return;
        }

        if (Schema::connection('redirect-sqlite')->hasTable('redirects')) {
            return;
        }

        $defaultConnection = DB::getDefaultConnection();
        DB::setDefaultConnection('redirect-sqlite');
        require_once(__DIR__ . '/../database/migrations/create_redirect_redirects_table.php.stub');
        (new \CreateRedirectRedirectsTable())->up();
        DB::setDefaultConnection($defaultConnection);
    }

    protected function generalConnectionIsBuiltinSqlite()
    {
        if(config('statamic.redirect.connection') === 'redirect-sqlite') {
            return true;
        }

        if(config('statamic.redirect.connection') === 'redirect') {
            return true;
        }

        return false;
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
