<?php

namespace App\Http\Controllers\Api\V2\Applet;

use Costa92\Wechat\Method\Method;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use League\Flysystem\Exception;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Redis;
use Wechat as LaverelWechat;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    private $table ="applet_user";
    private $visitTable ="applet_user_visit";

    public function __construct()
    {
//        parent::__construct();
    }

    /**
     * 验证微信登录并将用户信息保存到数据库,并且返回出数据
     * @param Request $request
     * @return mixed
     */
    public function getAppletUser(Request $request){
    	$session3rd  = $request->get("sessionID");
    	$id = $request->get("id");
        $code = $request->get("code");
        $iv = $request->get("iv");
        $rawData = $request->get("rawData");
        $signature = $request->get("signature");
        $encryptedData = $request->get("encryptedData");
        if(!$session3rd){
        	if($id && $code && $iv && $rawData && $signature && $encryptedData){
        		$activity = $this->getActivity($id);
        		if(!$activity){
        			return $this->noData();
        		}
        		$rs = LaverelWechat::setParams(array($activity->wechat->appid,$activity->wechat->secret,$code,array('iv'=>$iv,'rawData'=>$rawData,'signature'=>$signature,'encryptedData'=>$encryptedData)))->WechatApplet("Verification");
        		if($rs){
        			return $this->json($rs);
        		}
        		return $this->errorJson(403,"No Get Wechat Data!");
        	}
        	return $this->noParams();
        }else{
        	$redis_sessionkey =  Redis::get($session3rd);
        	if(!$redis_sessionkey){
        		return $this->json(array('status'=>0,'msg'=>'key失效!!!'));
        	}
        	return $this->json(array('status'=>1));
        }
    }
    
    
    /**
     * 用户拒绝授权,保存用户openid到数据库,并且返回出数据
     * @param Request $request
     * @return mixed
     */
    public function getAppletDefectUser(Request $request){
    	$id = $request->get("id");
    	$code = $request->get("code");
    	if($id && $code){
    		$activity = $this->getActivity($id);
    		if(!$activity){
    			return $this->noData();
    		}
    		$rs = LaverelWechat::setParams(array($activity->wechat->appid,$activity->wechat->secret,$code))->WechatApplet("UserId");
    		if($rs){
    			return $this->json($rs);
    		}
    		return $this->errorJson(403,"No Get Wechat Data!");
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 获取微信群的相关信息
     * @param Request $request
     * @return mixed
     */
    public function getAppletGroupInfo(Request $request){
    	$id = $request->get("id");
    	$code = $request->get("code");
    	$iv = $request->get("iv");
    	$encryptedData = $request->get("encryptedData");
    	if($id && $code && $iv && $encryptedData){
    		$activity = $this->getActivity($id);
    		if(!$activity){
    			return $this->noData();
    		}
    		$rs = LaverelWechat::setParams(array($activity->wechat->appid,$activity->wechat->secret,$code,array('iv'=>$iv,'encryptedData'=>$encryptedData)))->WechatApplet("WechatGroup");
    		if($rs){
    			return $this->json($rs);
    		}
    		return $this->errorJson(403,"No Get Wechat Data!");
    	}
    	return $this->noParams();
    }


    /**
     * 获取微信小程序的小程序码
     * @param Request $request
     * @return mixed
     */
    public function getWxaCode(Request $request){
        $id = $request->get("id");
        $scene = $request->get("scene");
        $page = $request->get("page");
        $width = $request->get("width");
        if($id && $scene && $width){
            if(!is_numeric($width)){
                return $this->errorJson('202','数据类型错误');
            }
            if($width <= 0){
                return $this->errorJson('202','width error');
            }
            if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $width))
            {
                return $this->errorJson('202','width error');
            }
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            $rs = LaverelWechat::setParams(array($activity->wechat->appid,$activity->wechat->secret,array('scene'=>$scene,'page'=>$page,'width'=>$width)))->WechatApplet("WxaCode");
            if($rs){
                return $this->json(array('url'=>$rs));
            }
            return $this->errorJson(403,"No Get Data!");
        }
        return $this->noParams();
    }


    /**
     * 用户访问小程序
     * @param  Request $request
     * @return mixed
     */
    public function saveVisit(Request $request)
    {
        $id = $request->get("id");
        $sid = $request->get("sid");
        if ($id && $sid) {
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            $user = DB::table($this->table)->select('id')->where('s_uid', $sid)->first();
            if (!$user) {
                return $this->noData();
            }
            DB::table($this->visitTable)->insert(array('a_id'=>$id,'x_uid'=>$user->id,'addtime'=>time()));
            $user_table = $this->table;
            $visit_table = $this->visitTable;
            $visitData = DB::table("$user_table")->join("$visit_table","$visit_table.x_uid","=","$user_table.id")
                            ->select("$user_table.nickname","$user_table.avatarurl")
                            ->where("$visit_table.a_id",$id)
                            ->orderBy("$visit_table.id","desc")
                            ->take(5)->get();
                            
            return $this->json($visitData);
        }
        return $this->noParams();
    }
    
    
}
