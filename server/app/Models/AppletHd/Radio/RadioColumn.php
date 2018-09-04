<?php

namespace App\Models\AppletHd\Radio;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppletHd\Radio\Radio;

class RadioColumn extends Model
{
    protected $table = "radio_column";

    protected $fillable=[
        'radio_id','column_name','column_navigation','column_picture','paixu'
    ];
    
    public function radio()
    {
    	return $this->hasOne(Radio::class,'id','radio_id');
    }
}
