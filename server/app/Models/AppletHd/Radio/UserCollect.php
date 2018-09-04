<?php

namespace App\Models\AppletHd\Radio;

use App\Models\AppletHd\Radio\Reports;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserCollect extends BaseModel
{
	public $timestamps = false; //时间戳
	
    protected $table = "radio_user_collect";

    protected $fillable=[
        'x_uid','report_id','type','collect_time','addtime'
    ];
    
    public function report(){
    	return $this->hasOne(Reports::class,'id','report_id');
    }
    
}
