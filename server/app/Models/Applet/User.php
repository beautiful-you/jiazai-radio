<?php

namespace App\Models\Applet;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public $timestamps = false; //时间戳
	
    protected $table = "applet_user";

    protected $fillable=[
    ];
    
}
