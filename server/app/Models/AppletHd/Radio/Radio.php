<?php

namespace App\Models\AppletHd\Radio;

use App\Models\BaseModel;
use App\Models\AppletHd\Radio\RadioPictures;

use Illuminate\Database\Eloquent\Model;

class Radio extends BaseModel
{
    protected $table = "radio";

    protected $fillable=[
        'radio_name'
    ];
    
    public function pictures(){
    	return $this->hasMany(RadioPictures::class,'radio_id');
    }
}
