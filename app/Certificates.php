<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificates extends Model
{
    protected $table = 'experience_certificates';

    protected $appends = [
        'bg_url',
    ];


    public function bgUrl()
    {
            return Storage::url($this->attributes['bg_file_url'])."?time=".time();
    }

    public function getBgUrlAttribute()
    {
        return $this->bgUrl();
    }
}
