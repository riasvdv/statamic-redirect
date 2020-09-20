<?php

namespace Rias\StatamicRedirect\Tests\Unit\Repositories;

use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Repositories\FileRedirectRepository;
use Rias\StatamicRedirect\Tests\TestCase;
use Statamic\Facades\Folder;

class FileRedirectRepositoryTest extends TestCase
{
    /** @var FileRedirectRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(FileRedirectRepository::class);
    }

    /** @test * */
    public function redirects_have_a_storage_path()
    {
        $this->assertEquals(base_path('content/redirects'), $this->repository->basePath());
    }

    /** @test * */
    public function saving_a_redirect_saves_a_new_file_and_sets_id()
    {
        $redirect = new Redirect([
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);

        $this->assertCount(0, Folder::getFiles($this->repository->basePath(), true));

        $this->repository->save($redirect);

        $this->assertEquals(1, $redirect->id);
        $this->assertCount(1, Folder::getFiles($this->repository->basePath(), true));
    }

    /** @test * */
    public function it_ca_retrieve_all_records()
    {
        $redirect1 = new Redirect([
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);
        $this->repository->save($redirect1);
        $redirect2 = new Redirect([
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);
        $this->repository->save($redirect2);
        $redirect3 = new Redirect([
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);
        $this->repository->save($redirect3);

        $this->assertCount(3, $this->repository->all());
        $this->assertTrue($this->repository->all()[0] instanceof Redirect);
    }

    /** @test * */
    public function a_redirect_has_a_specific_path()
    {
        $redirect = new Redirect([
            'id' => 1,
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);

        $this->assertEquals(base_path("content/redirects/1.yaml"), $this->repository->path($redirect));
    }

    /** @test * */
    public function a_redirect_can_be_deleted()
    {
        $redirect = new Redirect([
            'source' => '/test',
            'destination' => '/test2',
            'type' => '301',
        ]);
        $this->repository->save($redirect);

        $this->assertCount(1, Folder::getFiles($this->repository->basePath(), true));

        $this->repository->delete($redirect);

        $this->assertCount(0, Folder::getFiles($this->repository->basePath(), true));
    }

    /** @test * */
    public function it_can_be_found_for_an_exact_url()
    {
        $redirect = new Redirect([
            'source' => '/from',
            'destination' => '/to',
            'type' => '301',
            'match_type' => MatchTypeEnum::EXACT,
        ]);
        $this->repository->save($redirect);

        $this->assertNotNull($this->repository->findForUrl('/from'));
        $this->assertNotNull($this->repository->findForUrl('from'));
    }

    /** @test * */
    public function it_can_be_found_for_a_regex_expression()
    {
        $redirect = new Redirect([
            'source' => '/from(.*)',
            'destination' => '/to',
            'match_type' => MatchTypeEnum::REGEX,
        ]);
        $this->repository->save($redirect);

        $this->assertNotNull($this->repository->findForUrl('/fromabc'));
    }

    /** @test * */
    public function it_sets_the_destination_with_capture_groups()
    {
        $redirect = new Redirect([
            'source' => '/from(.*)',
            'destination' => '/to$1',
            'match_type' => MatchTypeEnum::REGEX,
        ]);
        $this->repository->save($redirect);

        $redirect = $this->repository->findForUrl('/fromabc');

        $this->assertEquals('/toabc', $redirect->destination);
    }
}
