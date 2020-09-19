<?php

namespace Rias\StatamicRedirect\Tests\Unit\Models;

use Illuminate\Support\Str;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Models\Redirect;
use Rias\StatamicRedirect\Tests\TestCase;
use Statamic\Facades\Folder;

class RedirectTest extends TestCase
{
    /** @test * */
    public function redirects_have_a_storage_path()
    {
        $this->assertEquals(base_path('content/redirects'), Redirect::preparePath());
    }

    /** @test * */
    public function it_can_make_a_redirect_instance()
    {
        $redirect = Redirect::make([
            'source' => '/from',
            'destination' => '/to',
        ]);

        $this->assertEquals('from', $redirect->slug);
        $this->assertEquals('/from', $redirect->source);
        $this->assertEquals('/to', $redirect->destination);
        $this->assertTrue($redirect->enabled);
        $this->assertEquals('301', $redirect->type);
        $this->assertEquals(MatchTypeEnum::EXACT, $redirect->match_type);

        $this->assertCount(0, Folder::getFiles(Redirect::preparePath(), true));
    }

    /** @test * */
    public function it_can_make_a_redirect_instance_with_all_attributes()
    {
        $redirect = Redirect::make([
            'source' => '/from',
            'destination' => '/to',
            'enabled' => false,
            'type' => '302',
            'match_type' => MatchTypeEnum::REGEX,
        ]);

        $this->assertEquals('from', $redirect->slug);
        $this->assertEquals('/from', $redirect->source);
        $this->assertEquals('/to', $redirect->destination);
        $this->assertFalse($redirect->enabled);
        $this->assertEquals('302', $redirect->type);
        $this->assertEquals(MatchTypeEnum::REGEX, $redirect->match_type);
    }

    /** @test * */
    public function saving_or_creating_a_redirect_saves_a_new_file()
    {
        $redirect = Redirect::make([
            'source' => '/from',
            'destination' => '/to',
        ]);

        $this->assertCount(0, Folder::getFiles(Redirect::preparePath(), true));

        $redirect->save();

        $this->assertCount(1, Folder::getFiles(Redirect::preparePath(), true));

        Redirect::create([
            'source' => '/from2',
            'destination' => '/to2',
        ]);

        $this->assertCount(2, Folder::getFiles(Redirect::preparePath(), true));
    }

    /** @test * */
    public function it_can_be_transformed_to_an_array_or_json()
    {
        $uuid = Str::uuid()->toString();

        $redirect = Redirect::make([
            'source' => '/from',
            'destination' => '/to',
            'enabled' => false,
            'type' => '302',
            'match_type' => MatchTypeEnum::REGEX,
        ]);

        $this->assertEquals([
            'slug' => 'from',
            'source' => '/from',
            'destination' => '/to',
            'enabled' => false,
            'type' => '302',
            'match_type' => MatchTypeEnum::REGEX,
        ], $redirect->toArray());

        $this->assertEquals(json_encode([
            'slug' => 'from',
            'enabled' => false,
            'source' => '/from',
            'destination' => '/to',
            'type' => '302',
            'match_type' => MatchTypeEnum::REGEX,
        ]), $redirect->toJson());
    }

    /** @test * */
    public function it_ca_retrieve_all_records()
    {
        Redirect::create([
            'source' => '/from',
            'destination' => '/to',
        ]);
        Redirect::create([
            'source' => '/from2',
            'destination' => '/to2',
        ]);
        Redirect::create([
            'source' => '/from3',
            'destination' => '/to3',
        ]);

        $this->assertCount(3, Redirect::all());
        $this->assertTrue(Redirect::all()->first() instanceof Redirect);
    }

    /** @test * */
    public function a_redirect_has_a_specific_path()
    {
        $redirect = Redirect::create([
            'source' => '/from',
            'destination' => '/to',
        ]);

        $this->assertEquals(base_path("content/redirects/from.yaml"), $redirect->path());
    }

    /** @test * */
    public function a_redirect_can_be_deleted()
    {
        $redirect = Redirect::create([
            'source' => '/from',
            'destination' => '/to',
        ]);

        $this->assertCount(1, Folder::getFiles(Redirect::preparePath(), true));

        $redirect->delete();

        $this->assertCount(0, Folder::getFiles(Redirect::preparePath(), true));
    }

    /** @test * */
    public function it_can_be_found_for_an_exact_url()
    {
        Redirect::create([
            'source' => '/from',
            'destination' => '/to',
            'match_type' => MatchTypeEnum::EXACT,
        ]);

        $this->assertNotNull(Redirect::findForUrl('/from'));
        $this->assertNotNull(Redirect::findForUrl('from'));
    }

    /** @test * */
    public function it_can_be_found_for_a_regex_expression()
    {
        Redirect::create([
            'source' => '/from(.*)',
            'destination' => '/to',
            'match_type' => MatchTypeEnum::REGEX,
        ]);

        $this->assertNotNull(Redirect::findForUrl('/fromabc'));
    }

    /** @test * */
    public function it_sets_the_destination_with_capture_groups()
    {
        Redirect::create([
            'source' => '/from(.*)',
            'destination' => '/to$1',
            'match_type' => MatchTypeEnum::REGEX,
        ]);

        $redirect = Redirect::findForUrl('/fromabc');

        $this->assertEquals('/toabc', $redirect->destination);
    }
}
