<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Content extends Model
{
    protected $appends = ['original_bodytext'];

    public function externalContent()
    {
        return ExternalContent::find($this->external_content_id);
    }

    public function getHeaderAttribute($value)
    {
        return Cache::tags(['content_'.$this->id])->rememberForever('content_header_'.$this->id, function() use ($value) {
            if (strlen(trim($value)) > 0) {
                return $value;
            } else {
                return $this->externalContent()->header;
            }
        });
    }

    public function getBodytextAttribute($value)
    {
        return Cache::tags(['content_'.$this->id])->rememberForever('content_bodytext_'.$this->id, function() use ($value) {
            if (strlen(trim($value)) > 0) {
                return $value;
            } else {
                return $this->externalContent()->bodytext;
            }
        });
    }

    public function getOriginalBodytextAttribute()
    {
        return Cache::tags(['content_'.$this->id])->rememberForever('content_original_bodytext_'.$this->id, function() {
            return $this->externalContent()->bodytext;
        });
    }

    public function publish()
    {
        $external_content = $this->externalContent();
        $external_content->bodytext = $this->bodytext;
        Cache::tags(['content_'.$this->id])->flush();
        return $external_content->save();
    }

    public static function checkForExisting()
    {
        return Content::where('external_content_id', request()->input('id'))->get()->count();
    }

}
