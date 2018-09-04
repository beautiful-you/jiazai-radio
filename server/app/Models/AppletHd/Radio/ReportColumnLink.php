<?php

namespace App\Models\AppletHd\Radio;

use Illuminate\Database\Eloquent\Model;

class ReportColumnLink extends Model
{
	public $timestamps = false; //时间戳
	
    protected $table = "radio_column_report_links";

    protected $fillable=[
        'report_id','column_id'
    ];
    
}
