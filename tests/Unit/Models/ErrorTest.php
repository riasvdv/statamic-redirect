<?php

namespace Rias\StatamicRedirect\Tests\Unit\Models;

use Illuminate\Support\Str;
use Rias\StatamicRedirect\Models\Error;
use Rias\StatamicRedirect\Tests\TestCase;
use Statamic\Facades\Folder;

class ErrorTest extends TestCase
{
    /** @test * */
    public function errors_have_a_storage_path()
    {
        $this->assertEquals(storage_path('redirect/errors'), Error::preparePath());
    }

    /** @test * */
    public function it_can_make_an_error_instance()
    {
        $error = Error::make([
            'url' => '/test',
        ]);

        $this->assertNotNull($error->id);
        $this->assertFalse($error->handled);
        $this->assertEquals(now()->timestamp, $error->date);
        $this->assertEquals('/test', $error->url);

        $this->assertCount(0, Folder::getFiles(Error::preparePath(), true));
    }

    /** @test * */
    public function it_can_make_an_error_instance_with_all_attributes()
    {
        $uuid = Str::uuid()->toString();

        $error = Error::make([
            'url' => '/test',
            'handled' => true,
            'date' => now()->addDay()->timestamp,
            'id' => $uuid,
        ]);

        $this->assertEquals($uuid, $error->id);
        $this->assertTrue($error->handled);
        $this->assertEquals(now()->addDay()->timestamp, $error->date);
        $this->assertEquals('/test', $error->url);

        $this->assertCount(0, Folder::getFiles(Error::preparePath(), true));
    }

    /** @test * */
    public function saving_or_creating_an_error_saves_a_new_file()
    {
        $error = Error::make([
            'url' => '/test',
        ]);

        $this->assertCount(0, Folder::getFiles(Error::preparePath(), true));

        $error->save();

        $this->assertCount(1, Folder::getFiles(Error::preparePath(), true));

        Error::create(['url' => '/test2']);

        $this->assertCount(2, Folder::getFiles(Error::preparePath(), true));
    }

    /** @test * */
    public function it_can_be_transformed_to_an_array_or_json()
    {
        $uuid = Str::uuid()->toString();

        $error = Error::make([
            'url' => '/test',
            'handled' => true,
            'date' => now()->addDay()->timestamp,
            'id' => $uuid,
        ]);

        $this->assertEquals([
            'id' => $uuid,
            'url' => '/test',
            'date' => now()->addDay()->timestamp,
            'handled' => true,
        ], $error->toArray());

        $this->assertEquals(json_encode([
            'id' => $uuid,
            'url' => '/test',
            'date' => now()->addDay()->timestamp,
            'handled' => true,
        ]), $error->toJson());
    }

    /** @test * */
    public function it_can_retrieve_all_records()
    {
        Error::create(['url' => '/test1']);
        Error::create(['url' => '/test2']);
        Error::create(['url' => '/test3']);

        $this->assertCount(3, Error::all());
        $this->assertTrue(Error::all()->first() instanceof Error);
    }

    /** @test * */
    public function an_error_has_a_specific_path()
    {
        $uuid = Str::uuid()->toString();
        $error = Error::create(['id' => $uuid, 'url' => '/test1']);
        $date = now()->format('Y/m/d');

        $this->assertEquals(storage_path("redirect/errors/{$date}/{$uuid}.yaml"), $error->path());
    }

    /** @test * */
    public function an_error_can_be_deleted()
    {
        $error = Error::create(['url' => '/test1']);

        $this->assertCount(1, Folder::getFiles(Error::preparePath(), true));

        $error->delete();

        $this->assertCount(0, Folder::getFiles(Error::preparePath(), true));
    }
}
