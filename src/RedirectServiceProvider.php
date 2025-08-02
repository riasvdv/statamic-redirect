<?php

namespace Rias\StatamicRedirect;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Actions\Delete;
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
use Rias\StatamicRedirect\UpdateScripts\AddDescriptionColumnToRedirectsTable;
use Rias\StatamicRedirect\UpdateScripts\AddHitsCount;
use Rias\StatamicRedirect\UpdateScripts\ClearErrors;
use Rias\StatamicRedirect\UpdateScripts\IncreaseUrlSizeOnErrors;
use Rias\StatamicRedirect\UpdateScripts\IncreaseUrlSizeOnRedirects;
use Rias\StatamicRedirect\UpdateScripts\MoveRedirectsToDefaultSite;
use Rias\StatamicRedirect\UpdateScripts\RenameLocaleToSiteOnRedirectsTable;
use Rias\StatamicRedirect\UpdateScripts\Version4Upgrade;
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
        AddDescriptionColumnToRedirectsTable::class,
        IncreaseUrlSizeOnRedirects::class,
        IncreaseUrlSizeOnErrors::class,
        Version4Upgrade::class,
    ];

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
        ],
        'publicDirectory' => 'resources/dist',
        'hotFile' => __DIR__.'/../resources/dist/hot',
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

    protected $actions = [
        Delete::class,
    ];

    public function register()
    {
        $this->registerAddonConfig();

        $this->app->singleton(RedirectRepository::class, function () {
            return $this->app->get($this->getRedirectRepository());
        });
    }

    public function boot(): void
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

            File::ensureDirectoryExists(storage_path('redirect'));

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'redirect');

            $this
                ->bootAddonNav()
                ->bootErrors()
                ->bootRedirects()
                ->bootPermissions();
        });

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../database/migrations/create_redirect_error_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_redirect_error_tables.php'),
            __DIR__.'/../database/migrations/increase_redirect_error_table_url_length.php.stub' => database_path('migrations/'.date('Y_m_d_His', time() + 1).'_increase_redirect_error_table_url_length.php'),
        ], 'statamic-redirect-error-migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/create_redirect_redirects_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_redirect_redirects_table.php'),
            __DIR__.'/../database/migrations/add_description_to_redirect_redirects_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time() + 1).'_add_description_to_redirect_redirects_table.php'),
            __DIR__.'/../database/migrations/increase_redirect_redirects_table_url_length.php.stub' => database_path('migrations/'.date('Y_m_d_His', time() + 2).'_increase_redirect_redirects_table_url_length.php'),
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

    protected function bootAddonNav(): self
    {
        Nav::extend(function (\Statamic\CP\Navigation\Nav $nav) {
            $items = [];

            if (config('statamic.redirect.log_errors') && config('statamic.redirect.dashboard_enabled', true)) {
                $items['Dashboard'] = cp_route('redirect.index');
            }

            $items['Redirects'] = cp_route('redirect.redirects.index');

            if (config('statamic.redirect.log_errors')) {
                $items['Errors'] = cp_route('redirect.errors.index');
            }

            $navItem = $nav->item('Redirect');
            $navItem->section('Tools');
            $navItem->route(
                config('statamic.redirect.log_errors') && config('statamic.redirect.dashboard_enabled', true)
                    ? 'redirect.index'
                    : 'redirect.redirects.index'
            );
            $navItem->icon('arrow-up-right');
            $navItem->can('view redirects');
            $navItem->children($items);
        });

        return $this;
    }

    protected function bootErrors(): self
    {
        if (! config('statamic.redirect.log_errors')) {
            return $this;
        }

        if (config('statamic.redirect.error_connection', 'redirect-sqlite') !== 'redirect-sqlite' && ! $this->generalConnectionIsBuiltinSqlite()) {
            return $this;
        }

        $this->createSqliteConnection();

        if (config('statamic.redirect.run_migrations')) {
            $defaultConnection = DB::getDefaultConnection();
            DB::setDefaultConnection('redirect-sqlite');

            if (! Schema::hasTable('errors')) {
                require_once __DIR__.'/../database/migrations/create_redirect_error_tables.php.stub';
                (new \CreateRedirectErrorTables)->up();
            }

            if (! Schema::hasColumn('errors', 'url_md5')) {
                require_once __DIR__.'/../database/migrations/increase_redirect_error_table_url_length.php.stub';
                (new \IncreaseRedirectErrorTableUrlLength)->up();
            }

            DB::setDefaultConnection($defaultConnection);
        }

        return $this;
    }

    protected function bootRedirects(): self
    {
        $connection = config('statamic.redirect.redirect_connection', 'stache');

        if ($connection === 'stache') {
            $redirectStore = new RedirectStore;
            $redirectStore->directory(config('statamic.redirect.redirect_store', base_path('content/redirects')));
            app(Stache::class)->registerStore($redirectStore);

            return $this;
        }

        if ($this->generalConnectionIsBuiltinSqlite()) {
            $this->createSqliteConnection();
        }

        if ($connection === 'default') {
            $connection = DB::getDefaultConnection();
        }

        if (Schema::connection($connection)->hasTable('redirects')) {
            return $this;
        }

        if (config('statamic.redirect.run_migrations')) {
            $defaultConnection = DB::getDefaultConnection();
            DB::setDefaultConnection($connection);

            require_once __DIR__.'/../database/migrations/create_redirect_redirects_table.php.stub';
            (new \CreateRedirectRedirectsTable)->up();
            require_once __DIR__.'/../database/migrations/add_description_to_redirect_redirects_table.php.stub';
            (new \AddDescriptionToRedirectRedirectsTable)->up();
            require_once __DIR__.'/../database/migrations/increase_redirect_redirects_table_url_length.php.stub';
            (new \IncreaseRedirectRedirectsTableUrlLength)->up();
            require_once __DIR__.'/../database/migrations/version_4_upgrade.php.stub';
            (new \Version4UpgradeMigration)->up();

            DB::setDefaultConnection($defaultConnection);
        }

        return $this;
    }

    protected function createSqliteConnection(): void
    {
        $sqlitePath = storage_path('redirect/redirect.sqlite');
        $this->ensureDatabaseExists($sqlitePath);

        if (Config::has('database.connections.redirect-sqlite')) {
            return;
        }

        Config::set('database.connections.redirect-sqlite', [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
        ]);
    }

    protected function ensureDatabaseExists($sqlitePath): void
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

    protected function generalConnectionIsBuiltinSqlite()
    {
        if (config('statamic.redirect.connection') === 'redirect-sqlite') {
            return true;
        }

        if (config('statamic.redirect.connection') === 'redirect') {
            return true;
        }

        return false;
    }

    protected function registerAddonConfig(): self
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
