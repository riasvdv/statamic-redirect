<?php

namespace Rias\StatamicRedirect\Tests;

use Facades\Statamic\Console\Processes\Composer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Extend\Manifest;
use Statamic\Facades\User;
use Statamic\Statamic;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->makeFaker();

        Error::truncate();
        Hit::truncate();
        Redirect::all()->each->delete();
        Composer::shouldReceive('installedVersion')->andReturn('v3.3');
    }

    /**
     * Sign in a Statamic user as admin
     * @return mixed
     */
    protected function asAdmin()
    {
        $user = User::make();
        $user->id(1)->email('hey@rias.be')->makeSuper();
        $this->be($user);

        return $user;
    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Statamic\Providers\StatamicServiceProvider::class,
            \Wilderborn\Partyline\ServiceProvider::class,
            \Rias\StatamicRedirect\RedirectServiceProvider::class,
        ];
    }

    /**
     * Load package alias
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    /**
     * Load Environment
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'rias/statamic-redirect' => [
                'id' => 'rias/statamic-redirect',
                'namespace' => 'Rias\\StatamicRedirect\\',
            ],
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->artisan('migrate', ['--database' => 'testing']);

        include_once __DIR__ . '/../database/migrations/create_redirect_error_tables.php.stub';
        (new \CreateRedirectErrorTables())->up();
    }

    /**
     * Resolve the Application Configuration and set the Statamic configuration
     * @param \Illuminate\Foundation\Application $app
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets', 'cp', 'forms', 'routes', 'static_caching',
            'sites', 'stache', 'system', 'users',
        ];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require(__DIR__ . "/../vendor/statamic/cms/config/{$config}.php"));
        }

        // Setting the user repository to the default flat file system
        $app['config']->set('statamic.users.repository', 'file');

        // Assume the pro edition within tests
        $app['config']->set('statamic.editions.pro', true);

        Statamic::pushCpRoutes(function () {
            return require_once realpath(__DIR__ . '/../routes/cp.php');
        });

        // Define redirect config settings for all of our tests
        $app['config']->set("statamic.redirect", require(__DIR__ . "/../config/redirect.php"));
    }
}
