<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalContent extends Model
{

    protected $connection = 'content';
    protected $table = 'tt_content';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    public static function getAll()
    {
        return self::whereIn('uid', Content::all()->pluck('external_content_id')->all())->get();
    }
    
}
