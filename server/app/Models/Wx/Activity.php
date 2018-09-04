<?php

namespace App\Models\Wx;

use App\Models\System\Sms\Tpl;
use App\Models\Wx\Activity\Red;
use App\Models\Wx\Activity\SpecialRed;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wx\Activity\Red\Voice;
class Activity extends Model
{
    protected $table = "wechat_activity";

    protected $fillable=[
        'name','title','sql_name','pattern','wechat_id','share_title','share_description','share_img','sms_id'
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function wechat(){
        return $this->hasOne('App\Models\System\Wechat','id','wechat_id');
    }

    public function red(){
        return $this->hasMany(Red::class,'a_id');
    }

    public function voice(){
        return $this->hasMany(Voice::class,'a_id');
    }

    public function hasRed(){
        return $this->hasOne(Red::class,'a_id','id');
    }

    public function hasSpecialRed(){
        return $this->hasOne(SpecialRed::class,'a_id','id');
    }

    public function hasVoice(){
        return $this->hasOne(Voice::class,'a_id','id');
    }

    public function hasSms(){
        return $this->hasOne(Tpl::class,'id','sms_id');
    }
}
