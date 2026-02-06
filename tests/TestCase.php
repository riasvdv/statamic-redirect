<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\RedirectServiceProvider;
use Statamic\Facades\Entry;
use Statamic\Facades\Stache;
use Statamic\Facades\User;
use Statamic\Testing\AddonTestCase;

class TestCase extends AddonTestCase
{
    use LazilyRefreshDatabase;
    use WithFaker;

    protected string $addonServiceProvider = RedirectServiceProvider::class;

    protected $shouldFakeVersion = true;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->makeFaker();

        File::deleteDirectory(base_path('content/redirects'));
        Entry::all()->each->delete();
        Error::truncate();
        Hit::truncate();
        Redirect::all()->each->delete();
        Stache::clear();
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('statamic.api.enabled', true);
        $app['config']->set('statamic.graphql.enabled', true);
        $app['config']->set('statamic.editions.pro', true);
    }

    /**
     * Sign in a Statamic user as admin
     *
     * @return mixed
     */
    protected function asAdmin()
    {
        $user = User::make();
        $user->id(1)->email('hey@rias.be')->makeSuper();
        $this->be($user);

        return $user;
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->artisan('migrate', ['--database' => 'testing']);

        include_once __DIR__.'/../database/migrations/create_redirect_error_tables.php.stub';
        (new \CreateRedirectErrorTables)->up();

        include_once __DIR__.'/../database/migrations/increase_redirect_error_table_url_length.php.stub';
        (new \IncreaseRedirectErrorTableUrlLength)->up();
    }
}
