<?php

namespace App\Http\Controllers\Api\V1\Wx;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Wx\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Flysystem\Exception;
use Wechat as LaverelWechat;
use Illuminate\Support\Facades\DB;

class ActivityController extends BaseController
{

    public function __construct(){

    }
    /**
     * 获取jssdk数据
     * @param Request $request
     * @return string
     */
    public function jssdk(Request $request){
        try{
            $activity_id = $request->get("id");
            $url = $request->get("url");
            if($activity_id){
                $activity = $this->getActivity($activity_id);
                if(!$activity){
                     return $this->noData();
                }
                $rs= LaverelWechat::setParams(array($activity->wechat->appid,$activity->wechat->secret,$url))->JssdkMethod("getSignPackage");
              return $this->json($rs);
            }
            return $this->noParams();
        }catch (Exception $exception){
            return $exception->getMessage();
        }
    }

    /**
     * 分享信息
     */
    public function share(Request $request){
        try{
            $activity_id = $request->get("id");
            if($activity_id){
                $activity =Activity::find($activity_id);
                if(!$activity){
                    return $this->noData();
                }
                unset($activity->sql_name,$activity->sms_id,$activity->wechat_id);
                $activity->share_img = $this->getImageHost().$activity->share_img;
                return $this->json($activity);
            }
            return $this->noParams();
        }catch (Exception $exception){
            return $exception->getMessage();
        }
    }

    /**
     * 获取微信AppId
     * @param Request $request
     * @return mixed
     */
    public function getAppId(Request $request){
         $id = $request->get("id");
         if($id){
             $activity = $this->getActivity($id);
             if(!$activity){
                 return $this->noData();
             }
             return $this->json(array('appid'=>$activity->wechat->appid,'secret'=>$activity->wechat->secret,'scope'=>$activity->pattern));
         }
        return $this->noParams();
    }
    
    /**
     * 获取活动的标题
     * @param Request $request
     * @return mixed
     */
    public function getTitle(Request $request){
    	$id = $request->get("id");
    	if($id){
    		$activity = $this->getActivity($id);
    		if(!$activity){
    			return $this->noData();
    		}
    		return $this->json(array('title'=>$activity->title));
    	}
    	return $this->noParams();
    }


    /**
     * 获取活动的访问量
     * @param Request $request
     * @return mixed
     */
    public function getVisits(Request $request){
        $id = $request->get("id");
        if($id){
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            $table = $activity->sql_name.'_visits';
            if(DB::select("SHOW TABLES LIKE '$table'")){
                $visits = DB::table($table)->sum('access_times');
                return $this->json(array('活动名称'=>$activity->name,'真实访问量'=>$visits,'初始值'=>$activity->visits,'总访问量'=>$activity->visits + $visits));
            }
            return $this->errorJson('202','没有数据!!!');
        }
        return $this->noParams();
    }


    /**
     * 获取活动的分享次数
     * @param Request $request
     * @return mixed
     */
    public function getShareInfo(Request $request){
        $id = $request->get("id");
        if($id){
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            $table = $activity->sql_name.'_share_log';
            if(DB::select("SHOW TABLES LIKE '$table'")){
                $shareTimes = DB::table($table)->sum('share_times');
                return $this->json(array('活动名称'=>$activity->name,'分享次数'=>$shareTimes));
            }
            return $this->errorJson('202','没有数据!!!');
        }
        return $this->noParams();
    }


    /**
     * 获取活动的剩余票数
     * @param Request $request
     * @return mixed
     */
    public function getSurplus(Request $request){
        $id = $request->get("id");
        if($id){
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            $table = $activity->sql_name.'_from';
            if(DB::select("SHOW TABLES LIKE '$table'")){
                $received = DB::table($table)->count();
                return $this->json(array('piece'=>$activity->act_vote - $received));
            }
            return $this->json(array('piece'=>$activity->act_vote));
        }
        return $this->noParams();
    }


    /**
     * 查询活动是否结束
     * @param Request $request
     * @return mixed
     */
    public function IsInvalid(Request $request){
        $id = $request->get("id");
        if($id){
            $activity = $this->getActivity($id);
            if(!$activity){
                return $this->noData();
            }
            if($activity->end_time){
                if(time() > strtotime($activity->end_time)){
                    return $this->json(array('type'=>0,'msg'=>'活动已结束'));
                }
                return $this->json(array('type'=>1,'msg'=>'活动进行中'));
            }
            return $this->errorJson("202","请设置活动结束时间!!");
        }
        return $this->noParams();
    }


    /**
     * 查询活动的表信息
     * @param Request $request
     * @return mixed
     */
    public function getActMessage(Request $request)
    {
        $id = $request->get("id");
        if ($id) {
            $activity = Activity::select('sql_name')->where('id',$id)->first();
            if(!$activity){
                return $this->noData();
            }
            return $this->json($activity);
        }
        return $this->noParams();
    }


}
