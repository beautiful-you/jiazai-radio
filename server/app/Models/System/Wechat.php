<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    protected $table = "system_wechat";

    protected $fillable=[
        'name','appid','secret','app_mchid','app_key','apiclient_cert','apiclient_key','rootca'
    ];

    protected $hidden = [
        'remember_token',
    ];
}
