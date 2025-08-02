<?php

namespace Rias\StatamicRedirect\Tests\Feature;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Rias\StatamicRedirect\Facades\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;
use Rias\StatamicRedirect\UpdateScripts\MoveRedirectsToDefaultSite;
use Statamic\Facades\Stache;

class MoveRedirectsToDefaultSiteTest extends TestCase
{
    #[Test]
    public function it_moves_redirects()
    {
        $redirectsDirectory = Stache::store('redirects')->directory();

        File::deleteDirectory($redirectsDirectory);
        File::ensureDirectoryExists($redirectsDirectory);

        file_put_contents($redirectsDirectory.'68f3b32b-b89d-4bb2-bbc3-367542228c71.yaml', <<<'yaml'
id: 68f3b32b-b89d-4bb2-bbc3-367542228c71
enabled: true
source: foo
destination: bar
type: '302'
match_type: exact
yaml);

        $script = (new MoveRedirectsToDefaultSite('rias/statamic-redirect'));

        $this->assertEquals(0, Redirect::all()->count());

        $this->assertDirectoryExists($redirectsDirectory);
        $this->assertFileExists($redirectsDirectory.'68f3b32b-b89d-4bb2-bbc3-367542228c71.yaml');

        $script->update();

        $this->assertEquals(1, Redirect::all()->count());

        $this->assertDirectoryExists($redirectsDirectory.'default');
        $this->assertFileDoesNotExist($redirectsDirectory.'68f3b32b-b89d-4bb2-bbc3-367542228c71.yaml');
        $this->assertFileExists($redirectsDirectory.'default/68f3b32b-b89d-4bb2-bbc3-367542228c71.yaml');
    }
}
