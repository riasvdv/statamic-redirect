<?php

namespace Rias\StatamicRedirect\Tests\Unit\Repositories;

use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\Repositories\FileErrorRepository;
use Rias\StatamicRedirect\Tests\TestCase;
use Statamic\Facades\Folder;

class FileErrorRepositoryTest extends TestCase
{
    /** @var FileErrorRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(FileErrorRepository::class);
    }

    /** @test * */
    public function errors_have_a_storage_path()
    {
        $this->assertEquals(storage_path('redirect/errors'), $this->repository->basePath());
    }

    /** @test * */
    public function saving_an_error_saves_a_new_file_and_sets_id()
    {
        $error = new Error([
            'url' => '/test',
            'date' => now()->timestamp,
        ]);

        $this->assertCount(0, Folder::getFiles($this->repository->basePath(), true));

        $this->repository->save($error);

        $this->assertEquals(1, $error->id);
        $this->assertCount(1, Folder::getFiles($this->repository->basePath(), true));
    }

    /** @test * */
    public function it_can_retrieve_all_records()
    {
        $error1 = new Error(['url' => '/test1', 'date' => now()->timestamp]);
        $this->repository->save($error1);
        $error2 = new Error(['url' => '/test2', 'date' => now()->timestamp]);
        $this->repository->save($error2);
        $error3 = new Error(['url' => '/test3', 'date' => now()->timestamp]);
        $this->repository->save($error3);

        $this->assertCount(3, $this->repository->all());
        $this->assertTrue($this->repository->all()[0] instanceof Error);
    }

    /** @test * */
    public function an_error_has_a_specific_path()
    {
        $error = new Error(['id' => 1, 'url' => '/test1', 'date' => now()->timestamp]);
        $date = now()->format('Y/m/d');

        $this->assertEquals(storage_path("redirect/errors/{$date}/1.yaml"), $this->repository->path($error));
    }

    /** @test * */
    public function an_error_can_be_deleted()
    {
        $error = new Error(['id' => 1, 'url' => '/test1', 'date' => now()->timestamp]);
        $this->repository->save($error);

        $this->assertCount(1, Folder::getFiles($this->repository->basePath(), true));

        $this->repository->delete($error);

        $this->assertCount(0, Folder::getFiles($this->repository->basePath(), true));
    }
}
