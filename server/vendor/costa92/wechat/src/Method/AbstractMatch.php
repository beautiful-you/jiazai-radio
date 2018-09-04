<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/4/29
 * Time: 下午7:00
 */

namespace Costa92\Wechat\Method;


abstract class AbstractMatch
{

    protected $class;

    public $uid;

    public $Activity;

    public $data;

    public $where;

    public $TableType;

    public function __construct()
    {
        $this->norvel = new Execute();
    }

    abstract public function save();

    abstract public function find();

    abstract  public function update();

    public function show($mathce){  //执行
       return  $this->$mathce();
    }

}