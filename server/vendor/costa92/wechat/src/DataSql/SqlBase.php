<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/27
 * Time: 下午5:50
 */

namespace Costa92\Wechat\DataSql;

use  Illuminate\Support\Facades\DB;
use Costa92\Wechat\DataSql\DataRunSql;
class SqlBase
{
    public $_table;
    public function __construct(){

    }

    public function isTable(){
        if(DB::select("SHOW TABLES LIKE '$this->_table'")){
            return true;
        }
        return false;
    }

    public function find($where=array(),$select=""){
        if($select){
            return DB::table($this->_table)->select($select)->where($where)->orderBy('id','desc')->first();
        }
        return DB::table($this->_table)->where($where)->orderBy('id','desc')->first();
    }


    public function runSql($method ="",$n="",$type=""){
    	if($n){
    		reflection(DataRunSql::class,$method,array($this->_table,$n,$type));
    	}else{
    		reflection(DataRunSql::class,$method,array($this->_table));
    	}
    }


}