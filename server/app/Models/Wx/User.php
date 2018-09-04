<?php

namespace App\Models\Wx;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false; //时间戳

    protected $table ="wx_users";

    protected $fillable =[
        'openid','nickname','sex','headimgurl','city','province','country','addtime'
    ];

    protected $hidden = [
         'remember_token',
    ];

}
