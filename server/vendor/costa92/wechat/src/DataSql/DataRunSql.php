<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/28
 * Time: 上午9:55
 */

namespace Costa92\Wechat\DataSql;
use Illuminate\Support\Facades\Schema;

class DataRunSql
{
    public function __construct(){

    }
    
    //小程序
    public function SqlAppletUser($table=""){
    
    	Schema::create($table,function ($table){
    		$table->engine="InnoDB";
    		$table->increments('id');
    		$table->char('openid',50);
    		$table->text("nickname")->nullable();
    		$table->tinyInteger('gender')->nullable();
    		$table->text("avatarurl")->nullable();
    		$table->char("language",25)->nullable();
    		$table->char('city',35)->nullable();
    		$table->char('province',35)->nullable();
    		$table->char('country',25)->nullable();
    		$table->char('appid',25);
    		$table->dateTime('addtime');
    		$table->rememberToken();
    	});
    	return true;
    }

    
}
