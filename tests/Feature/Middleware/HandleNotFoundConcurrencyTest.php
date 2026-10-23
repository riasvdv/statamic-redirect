<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;
use Rias\StatamicRedirect\Jobs\CleanErrorsJob;
use Statamic\Facades\Site;

// Opt in with REDIRECT_MYSQL_TESTS=1. Use a disposable database: these tests
// recreate its errors and hits tables. SQLite cannot exercise InnoDB row locks.
beforeEach(function () {
    if (! getenv('REDIRECT_MYSQL_TESTS')) {
        $this->markTestSkipped('Requires a disposable MySQL database and pcntl.');
    }

    config()->set('database.connections.redirect-mysql-test', [
        'driver' => 'mysql',
        'host' => getenv('REDIRECT_MYSQL_HOST') ?: '127.0.0.1',
        'port' => getenv('REDIRECT_MYSQL_PORT') ?: 3306,
        'database' => getenv('REDIRECT_MYSQL_DATABASE') ?: 'redirect_test',
        'username' => getenv('REDIRECT_MYSQL_USER') ?: 'root',
        'password' => getenv('REDIRECT_MYSQL_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]);
    config()->set('statamic.redirect.error_connection', 'redirect-mysql-test');
    config()->set('statamic.redirect.clean_errors_on_save', false);

    $schema = Schema::getFacadeRoot();
    Schema::swap(DB::connection('redirect-mysql-test')->getSchemaBuilder());

    try {
        Schema::dropIfExists('hits');
        Schema::dropIfExists('errors');
        (new CreateRedirectErrorTables)->up();
        (new IncreaseRedirectErrorTableUrlLength)->up();
        (new AddSiteToRedirectErrorsTable)->up();
    } finally {
        Schema::swap($schema);
    }

    Redirect::make()->source('/abc')->destination('/def')->save();
    Exceptions::fake();
});

it('keeps cleanup from splitting an in-flight error and hit', function (string $state, string $isolation) {
    config()->set('database.connections.redirect-mysql-test.isolation_level', $isolation);
    config()->set('statamic.redirect.keep_unique_errors', $state === 'count' ? 0 : 1000);

    if ($state !== 'new') {
        $error = Error::create(['site' => Site::default()->handle(), 'url' => '/abc']);
        if ($state === 'count') {
            $error->addHit(now()->subDay()->timestamp);
        }
    }

    config()->set('database.connections.redirect-observer-test', config('database.connections.redirect-mysql-test'));

    // Neither process may inherit a live MySQL socket from the other.
    DB::purge('redirect-mysql-test');
    [$parent, $child] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
    stream_set_timeout($parent, 10);
    stream_set_timeout($child, 10);
    $pid = pcntl_fork();
    if ($pid === -1) {
        throw new RuntimeException('Could not fork the cleanup worker.');
    }

    if ($pid === 0) {
        fclose($parent);

        try {
            if (fgets($child) !== "clean\n") {
                throw new RuntimeException('The request did not reach the logging barrier.');
            }
            $connection = DB::connection('redirect-mysql-test');
            $connection->statement('SET SESSION innodb_lock_wait_timeout = 10');
            fwrite($child, $connection->selectOne('SELECT CONNECTION_ID() AS id')->id."\n");
            (new CleanErrorsJob)->handle();
            fwrite($child, "cleaned\n");
        } catch (Throwable $e) {
            fwrite($child, get_class($e).': '.$e->getMessage()."\n");
        }
        fclose($child);
        exit(0);
    }

    fclose($child);
    $dispatcher = Error::getEventDispatcher();
    Error::setEventDispatcher(clone $dispatcher);
    $blocked = false;
    $cleanupResult = null;
    $barrier = function () use ($parent, &$blocked, &$cleanupResult) {
        if ($blocked || $cleanupResult !== null) {
            return;
        }
        fwrite($parent, "clean\n");
        $connectionId = (int) fgets($parent);
        $observer = DB::connection('redirect-observer-test');
        $deadline = microtime(true) + 5;

        do {
            $blocked = (bool) $observer->selectOne(
                'SELECT COUNT(*) AS waits FROM performance_schema.data_lock_waits w
                 JOIN performance_schema.threads t ON t.THREAD_ID = w.REQUESTING_THREAD_ID
                 WHERE t.PROCESSLIST_ID = ?',
                [$connectionId],
            )->waits;
            if ($blocked) {
                return;
            }
            $read = [$parent];
            $write = $except = [];
            if (stream_select($read, $write, $except, 0, 10000)) {
                $cleanupResult = trim(fgets($parent));

                return;
            }
        } while (microtime(true) < $deadline);

        throw new RuntimeException('Cleanup neither blocked nor finished.');
    };

    // New parents must be protected before their first hit; existing parents
    // must already be protected at lookup, before even the first UPDATE.
    if ($state === 'new') {
        Hit::creating($barrier);
    } else {
        Error::retrieved($barrier);
    }

    try {
        $response = app(HandleNotFound::class)->handle(Request::create('/abc'), fn () => new Response('', 404));
        $cleanupResult ??= trim(fgets($parent) ?: 'worker timed out');
    } finally {
        Error::setEventDispatcher($dispatcher);
        fclose($parent);
        pcntl_waitpid($pid, $status);
        DB::purge('redirect-observer-test');
    }

    expect($response->getStatusCode())->toBe(301)
        ->and($response->headers->get('Location'))->toBe(url('/def'))
        ->and($blocked)->toBeTrue()
        ->and($cleanupResult)->toBe('cleaned');
    Exceptions::assertNothingReported();

    // The waiting cleaner must see the committed hit, not delete a fresh
    // parent based on an earlier view. Count-based eviction remains allowed.
    expect(Error::count())->toBe($state === 'count' ? 0 : 1)
        ->and(Hit::count())->toBe($state === 'count' ? 0 : 1);
    if ($state !== 'count') {
        expect(Error::findByUrl('/abc')->hitsCount)->toBe(1);
    }
})->with(['new', 'orphan', 'count'])->with(['REPEATABLE READ', 'READ COMMITTED']);

it('logs a fresh error when cleanup has deleted a parent in an older snapshot', function (bool $countCleanup) {
    config()->set('statamic.redirect.keep_unique_errors', $countCleanup ? 0 : 1000);
    $error = Error::create(['site' => Site::default()->handle(), 'url' => '/abc']);
    if ($countCleanup) {
        $error->addHit(now()->subDay()->timestamp);
    }

    $connection = DB::connection('redirect-mysql-test');
    $connection->statement('SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ');
    $connection->beginTransaction();

    try {
        // Establish a repeatable-read snapshot containing the old parent.
        expect(Error::findByUrl('/abc')->id)->toBe($error->id);
        config()->set('database.connections.redirect-cleaner-test', config('database.connections.redirect-mysql-test'));
        config()->set('statamic.redirect.error_connection', 'redirect-cleaner-test');

        try {
            (new CleanErrorsJob)->handle();
            expect(Error::count())->toBe(0);
        } finally {
            config()->set('statamic.redirect.error_connection', 'redirect-mysql-test');
        }

        $response = app(HandleNotFound::class)->handle(Request::create('/abc'), fn () => new Response('', 404));
        $connection->commit();
    } finally {
        if ($connection->transactionLevel()) {
            $connection->rollBack();
        }
        DB::purge('redirect-cleaner-test');
    }

    $fresh = Error::findByUrl('/abc');
    expect($response->getStatusCode())->toBe(301)
        ->and($fresh)->not->toBeNull()
        ->and($fresh->id)->not->toBe($error->id)
        ->and($fresh->hitsCount)->toBe(1)
        ->and($fresh->hits()->count())->toBe(1);
    Exceptions::assertNothingReported();
})->with([false, true]);
