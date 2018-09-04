<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Wx\Activity;
use App\Models\Wx\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Response;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\ResponseFactory;
use Carbon\Carbon;
use Wechat as LaverelWechat;
use App\Models\Alcohol\Activity as AlcoholActivity;

class BaseController extends Controller
{
    use Helpers;
    public function __construct(ResponseFactory $response, Dispatcher $events)
    {
        $this->response = $response;
        $this->events = $events;
    }

    /**
     *
     * @param $data
     * @return mixed
     */
    public function json($data){
        return Response::json(array('code'=>200,'data'=>$data));
    }

    public function errorJson($code,$data){
        return Response::json(array('errcode'=>$code,'errmsg'=>$data));
    }

    /**
     *  获取图片
     * @return mixed
     */
    public function getImageHost(){
        return $config = config('admin.upload.host')."/";
    }


    public function getActivity($id,$red = false,$voice=false){
        if($red){
            if($voice){
                $activity =  Activity::with(array("wechat",'hasRed','hasVoice'))->find($id);
            }else{
                $activity =  Activity::with(array("wechat",'hasRed'))->find($id);
            }
        }else{
            $activity =  Activity::with("wechat")->find($id);
        }
        if($activity){
            return $activity;
        }
        return false;
    }


    public function getAlcoholActivity($id)
    {
        $activity =  AlcoholActivity::with("wechat")->find($id);
        if($activity){
            return $activity;
        }
        return false;
    }


    public function getSpecialActivity($id){
        $activity =  Activity::with(array("wechat",'hasSpecialRed'))->find($id);
        if($activity){
            return $activity;
        }
        return false;
    }


    public function getOtherUser($uid){
        return $this->getRequests(array('id'=>$uid),'WechatMethod','find');
    }

//生成微信用户表的token
    public  function setToken($openid =""){
        if($openid){
            $user = User::where(array('openid'=>$openid))->first();
            $token = JWTAuth::fromUser($user);
            //token写入数据库
            // DB::table('wx_users')->where('openid',$openid)->update(['remember_token'=>$token]);
            return $data =array(
                'token'=>$token,
                'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
                'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->toDateTimeString(),
            );
        }
        return false;
    }

    protected function refresh($old_token=""){
        if($old_token){
            $token=JWTAuth::refresh($old_token);
            JWTAuth::invalidate($old_token);
            return $token;
        }
        return false;
    }

    /**
     * 根据Token 获取用户值
     * @return mixed
     */
    protected function getUserToken(){
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    public function respond($event, $error, $status, $payload = []){
        $response = $this->events->fire($event, $payload, true);
        return $response ?: $this->response->json(['error' => $error], $status);
    }


    /**
     * 请求方法
     * @param array $data
     * @param string $mother
     * @return mixed
     */
    protected function getRequests($data=array(),$Service_mother="",$mother=""){
        if($data && $mother){
            $rs = LaverelWechat::setParams($data)->$Service_mother($mother);
            if($mother == "save"){
                return $this->json(array($Service_mother.'_id'=>$rs));
            }
            return $this->json($rs);
        }
        return $this->ErrSystem();
    }


    protected function noData(){  //请求活动为空的
        return $this->errorJson(400,'No Data');
    }

    protected  function noParams(){  //请求参数错误
        return $this->errorJson(1,'Params Error');
    }

    protected function ErrSystem(){
        return $this->errorJson(403,'System Error');
    }
}
