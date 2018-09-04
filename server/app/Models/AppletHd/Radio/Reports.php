<?php

namespace App\Models\AppletHd\Radio;

use App\Models\AppletHd\Radio\Radio;
use App\Models\AppletHd\Radio\RadioColumn;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $table = "radio_reports";

    protected $fillable=[
        'radio_id','report_title','report_introduce','listing_diagram','detail_drawing','audio','content','author','anchor','publish_time','collection','page_view','is_find','find_picture','recommend','status'
    ];
    
    public function radio()
    {
    	return $this->hasOne(Radio::class,'id','radio_id');
    }
    
    public function radio_column()
    {
    	return $this->belongsToMany(RadioColumn::class,'radio_column_report_links','report_id','column_id');
    }
}
