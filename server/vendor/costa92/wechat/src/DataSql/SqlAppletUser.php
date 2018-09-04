<?php
/**
 * Created by PhpStorm.
 * User: jack01
 * Date: 2017/6/15
 * Time: 中午12:35
 */

namespace Costa92\Wechat\DataSql;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SqlAppletUser extends SqlBase  implements DataSql
{
    public $_table;
    public  function __construct()
    {
        parent::__construct();
    }

    public function setTable($table)
    {
        $this->_table =$table;
        if(!$this->isTable($this->_table)){
            $this->runSql("SqlAppletUser");
        }
        return $this;
    }

    public function getTable()
    {
       return $this->_table;
    }


    public function find($uid="",$bool = true,$type = true)
    {
        if($type){
        	$where = $bool?array('id'=>$uid):array('openid'=>$uid);
        	return parent::find($where,array('id','s_uid','nickname','gender','avatarurl'));
        	
        }else{
        	$where = $bool?array('id'=>$uid):array('openid'=>$uid);
        	return parent::find($where,array('id','s_uid'));
        	
        }
    }


    public function save($data)
    {

    }

    public  function  update($where,$data){
        return DB::table($this->getTable())->where($where)->update($data);
    }
}