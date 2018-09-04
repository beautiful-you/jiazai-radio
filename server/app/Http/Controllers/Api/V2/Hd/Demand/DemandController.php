<?php

namespace App\Http\Controllers\Api\V2\Hd\Demand;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\DB;

class DemandController extends BaseController
{

    //提交需求
    public function addDemand(Request $request){
        $x_uid = $request->get('x_uid');   //微信id
        $name = $request->get('name');     //名字
        $type = $request->get('type');    //类型
        $phase = $request->get('phase');   //项目阶段
        $resources = $request->get('resources');  //已有资源
        $cooperate = $request->get('cooperate');  //需要配合的资源
        $page = $request->get('page');   //页面数
        $style = $request->get('style');    //风格
        $range = $request->get('range');    //预算范围
        $start_date = $request->get('start_date');   //开始时间
        $end_date = $request->get('end_date');   //结束时间
        $link_name = $request->get('link_name');   //联系人
        $link_phone = $request->get('link_phone');  //联系电话
        $link_phone2 = $request->get('link_phone2','');  //联系号码二号（非必须）
        $company = $request->get('company');   //公司名字
        $other = $request->get('other','');   //其他备注（非必须）

        if($x_uid && $name && $type && $phase && $resources && $cooperate && $page && $style && $range && $start_date && $end_date && $link_name && $link_phone && $company){
            $list = ['x_uid'=>$x_uid,'name'=>$name,'type'=>$type,'phase'=>$phase,'resources'=>$resources,'cooperate'=>$cooperate,'page'=>$page,'style'=>$style,'range'=>$range,
                'start_date'=>$start_date,'end_date'=>$end_date,'link_name'=>$link_name,'link_phone'=>$link_phone,'company'=>$company,'link_phone2'=>$link_phone2,'other'=>$other,'created_at'=>date('Y-m-d H:i:s', time())];
            DB::table('demand')->insert($list);
            return $this->json("ok");
        }
        return $this->noParams();
    }


}