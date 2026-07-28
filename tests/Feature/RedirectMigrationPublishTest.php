<?php

use Illuminate\Support\Facades\File;
use Rias\StatamicRedirect\RedirectServiceProvider;

it('reuses existing redirect migration filenames when publishing', function () {
    $originalDatabasePath = $this->app->databasePath();
    $isolatedDatabasePath = base_path('tests/tmp/redirect-migration-publish');
    $migrationsPath = "{$isolatedDatabasePath}/migrations";
    $this->app->useDatabasePath($isolatedDatabasePath);

    $existingMigrations = [
        $migrationsPath.'/2020_01_01_000000_create_redirect_redirects_table.php',
        $migrationsPath.'/2020_01_01_000001_add_description_to_redirect_redirects_table.php',
        $migrationsPath.'/2020_01_01_000002_increase_redirect_redirects_table_url_length.php',
        $migrationsPath.'/2020_01_01_000002_add_site_to_redirect_errors_table.php',
    ];

    try {
        File::ensureDirectoryExists($migrationsPath);

        foreach ($existingMigrations as $migration) {
            File::put($migration, '<?php');
        }

        $provider = new class($this->app) extends RedirectServiceProvider {
            public function resolveMigrationPath(string $migrationFileName, int $timestampOffset = 0): string
            {
                return $this->migrationPath($migrationFileName, $timestampOffset);
            }
        };

        expect($provider->resolveMigrationPath('create_redirect_redirects_table.php'))->toBe($existingMigrations[0]);
        expect($provider->resolveMigrationPath('add_description_to_redirect_redirects_table.php', 1))->toBe($existingMigrations[1]);
        expect($provider->resolveMigrationPath('increase_redirect_redirects_table_url_length.php', 2))->toBe($existingMigrations[2]);
        expect($provider->resolveMigrationPath('add_site_to_redirect_errors_table.php', 2))->toBe($existingMigrations[3]);
    } finally {
        File::deleteDirectory($isolatedDatabasePath);
        $this->app->useDatabasePath($originalDatabasePath);
    }
});
