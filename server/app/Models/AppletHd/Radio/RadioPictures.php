<?php

namespace App\Models\AppletHd\Radio;

use Illuminate\Database\Eloquent\Model;

class RadioPictures extends Model
{
    protected $table = "radio_pictures";

    protected $fillable=[
        'radio_id','radio_picture'
    ];
}
