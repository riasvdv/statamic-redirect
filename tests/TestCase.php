<?php

namespace Rias\StatamicRedirect\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Rias\StatamicRedirect\Models\Error;
use Rias\StatamicRedirect\Models\Redirect;
use Statamic\Extend\Manifest;
use Statamic\Facades\Folder;
use Statamic\Facades\Role;
use Statamic\Statamic;

class TestCase extends OrchestraTestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->makeFaker();

        Folder::delete(Error::preparePath());
        Folder::delete(Redirect::preparePath());
    }

    /**
     * Sign in a Statamic user
     * @param array $permissions
     * @return mixed
     */
    protected function asUser($permissions = [])
    {
        $role = Role::make()->handle('test')->title('Test')->addPermission($permissions)->save();

        $user = \Statamic\Facades\User::make();
        $user->id(1)->email('hey@rias.be')->assignRole($role);
        $this->be($user);

        return $user;
    }

    /**
     * Sign in a Statamic user as admin
     * @return mixed
     */
    protected function asAdmin()
    {
        $user = \Statamic\Facades\User::make();
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
                'id'        => 'rias/statamic-redirect',
                'namespace' => 'Rias\\StatamicRedirect\\',
            ],
        ];
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

        // Define butik config settings for all of our tests
        $app['config']->set("statamic.redirect", require(__DIR__ . "/../config/redirect.php"));
    }
}
