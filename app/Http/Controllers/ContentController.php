<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Content;
use App\ExternalContent;

class ContentController extends Controller
{

    public function index()
    {
        return view('content.index');
    }

    public function load()
    {
        $content = Content::all()->sortBy(function($item) {
            return $item->header;
        })->values();
        return response()->json(['contentItems' => $content]);
    }


    public function create()
    {

        if (Content::checkForExisting()) {
            return response()->json(['error' => 'That content item already exists']);
        }

        $check_external_exists = ExternalContent::findOrFail(request()->input('id'));

        $content = new Content;
        $content->external_content_id = request()->input('id');
        $content->save();

        return response()->json(['success' => 'Added '.$content->header]);
    }

    public function update()
    {
        $content = Content::findOrFail(request()->input('id'));

        $content->bodytext = request()->input('bodytext');
        $content->save();

        Cache::tags(['content_'.$content->id])->flush();
        return response()->json(['success' => 'Text saved']);

    }

    public function publish()
    {
        $content = Content::findOrFail(request()->input('id'));
        $content->publish();
        return response()->json(['success' => 'Content Published']);
    }

}
