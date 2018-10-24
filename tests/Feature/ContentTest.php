<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Content;
use App\ExternalContent;
use DB;

class ContentTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_content_exists()
    {

        $content = new Content;
        $content->external_content_id = '174';
        $content->save();

        $content = Content::all()->last();
        $this->assertEquals($content->external_content_id, '174');

    }


    public function test_loading_content_from_the_content_db()
    {

        $content = new Content;
        $content->external_content_id = '174';
        $content->save();

        $external_content = ExternalContent::getAll();
        $external_content = ExternalContent::getAll()->where('uid', '174')->first();

        $this->assertEquals($external_content->header, 'English 9');
    
    }



}
