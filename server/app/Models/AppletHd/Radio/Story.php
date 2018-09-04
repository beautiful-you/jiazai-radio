<?php

namespace App\Models\AppletHd\Radio;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppletHd\Radio\Radio;

class Story extends Model
{
    protected $table = "radio_story";

    protected $fillable=[
        'story_title','story_anchor','big_picture','small_picture','small_unchecked_picture','background_icon','background_unchecked_icon','story_audio','background_color','paixu'
    ];
}
