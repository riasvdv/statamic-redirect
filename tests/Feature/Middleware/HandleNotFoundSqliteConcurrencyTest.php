<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Schema;
use Rias\StatamicRedirect\Data\Error;
use Rias\StatamicRedirect\Data\Hit;
use Rias\StatamicRedirect\Http\Middleware\HandleNotFound;

beforeEach(function () {
    if (! function_exists('pcntl_fork')) {
        $this->markTestSkipped('Requires pcntl.');
    }

    // Locking needs a file: each process opens its own connection to it.
    $this->database = tempnam(sys_get_temp_dir(), 'redirect-sqlite-test');
    config()->set('database.connections.redirect-sqlite-file-test', [
        'driver' => 'sqlite',
        'database' => $this->database,
    ]);
    config()->set('statamic.redirect.error_connection', 'redirect-sqlite-file-test');
    config()->set('statamic.redirect.clean_errors_on_save', false);

    $schema = Schema::getFacadeRoot();
    Schema::swap(DB::connection('redirect-sqlite-file-test')->getSchemaBuilder());

    try {
        (new CreateRedirectErrorTables)->up();
        (new IncreaseRedirectErrorTableUrlLength)->up();
        (new AddSiteToRedirectErrorsTable)->up();
    } finally {
        Schema::swap($schema);
    }

    Exceptions::fake();
});

afterEach(function () {
    DB::purge('redirect-sqlite-file-test');
    @unlink($this->database);
});

it('logs both of two concurrent 404s on sqlite', function () {
    $handle = fn () => app(HandleNotFound::class)->handle(Request::create('/missing'), fn () => new Response('', 404));

    // Neither process may share the other's SQLite handle.
    DB::purge('redirect-sqlite-file-test');
    [$parent, $child] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
    stream_set_timeout($parent, 10);
    stream_set_timeout($child, 10);
    $pid = pcntl_fork();
    if ($pid === -1) {
        throw new RuntimeException('Could not fork the second request.');
    }

    if ($pid === 0) {
        fclose($parent);

        try {
            if (fgets($child) !== "request\n") {
                throw new RuntimeException('The first request did not reach its hit.');
            }
            fwrite($child, "started\n");
            $handle();
            Exceptions::assertNothingReported();
            fwrite($child, "logged\n");
        } catch (Throwable $e) {
            fwrite($child, get_class($e).': '.$e->getMessage()."\n");
        }
        fclose($child);
        exit(0);
    }

    fclose($child);
    $dispatcher = Hit::getEventDispatcher();
    Hit::setEventDispatcher(clone $dispatcher);
    $released = false;

    // Pause the first request after it saved the error, before it commits,
    // and let the second request look up and save the same error meanwhile.
    Hit::creating(function () use ($parent, &$released) {
        if ($released) {
            return;
        }
        $released = true;
        fwrite($parent, "request\n");
        fgets($parent);
        $read = [$parent];
        $write = $except = [];
        stream_select($read, $write, $except, 0, 500000);
    });

    try {
        $response = $handle();
        $childResult = trim(fgets($parent) ?: 'second request timed out');
    } finally {
        Hit::setEventDispatcher($dispatcher);
        fclose($parent);
        pcntl_waitpid($pid, $status);
    }

    expect($response->getStatusCode())->toBe(404)
        ->and($childResult)->toBe('logged');
    Exceptions::assertNothingReported();
    expect(Error::count())->toBe(1)
        ->and(Error::findByUrl('/missing')->hitsCount)->toBe(2)
        ->and(Hit::count())->toBe(2);
});
