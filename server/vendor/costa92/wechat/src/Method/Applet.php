<?php

/**
 * Created by PhpStorm.
 * User: jack01
 * Date: 2017/6/14
 * Time: 下午3:19
 */
namespace  Costa92\Wechat\Method;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Costa92\Wechat\Method\Wechat\WechatApi;
use Illuminate\Support\Facades\Redis;
use Costa92\Wechat\Applet\ErrorCode;
use Costa92\Wechat\Applet\WXBizDataCrypt;

class Applet extends BaseMathod
{
    private $table ="applet_user";
    private $groupTable ="applet_group";
    public function __construct(){

    }

    /**
     * 获取用户配置信息
     *
     */
    public function getWechat($appid = "",$sercen=""){

    }



    /**
     * 获取用用户信息
     * @param string $appid
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function Verification($appId ="",$secret="",$code="",$data){
    	
    	$user= WechatApi::getInstance()->setParams("appId",$appId)->setParams("secret",$secret)->setParams("code",$code)->getSessionKey();
    	if($user){
    		
    		$signature2 = sha1($data['rawData'] . $user->session_key);
    		if($signature2 !== $data['signature']){
    			return array('errcode'=>ErrorCode::$SignNotMatch,'msg'=>'签名不匹配!!');
    		}
    		
    		$pc = new WXBizDataCrypt($appId, $user->session_key);
    		$info = $pc->decryptData($data['encryptedData'], $data['iv'], $data );
    		if ($info['code'] !== 0) {
    			return array('errcode'=>ErrorCode::$EncryptDataNotMatch,'msg'=>"解密信息错误!!");
    		}
    		
    		$info = $info['data'];
    		if($info->openId != $user->openid){
    			return array('errcode'=>ErrorCode::$OpenIdNotMatch,'msg'=>"openId不匹配!!");
    		}
    		return $this->save($info,$user->session_key);
    	}
    	return false;
    }

    /**
     * 保存用户信息
     * @param $data
     * @return bool
     */
    public function save($data,$sessionKey){
        if($data && $sessionKey){
            $openid = $data->openId;
            $appid = $data->watermark->appid;
            $redis_user =  Redis::get('openid_'.$appid.'_'.$openid);
            if(!$redis_user){  // 已经保存
                $user = $this->findOpnenId($openid);
                if (!$user){
                    if(is_object($data)){
                        $data = objectToArray($data);
                        $data['addtime']   = date("Y-m-d H:i:s",time());
                        $data['openid']    = $data['openId'];
                        $data['nickname']  = $data['nickName'];
                        $data['avatarurl'] = $data['avatarUrl'];
                        $data['appid']     = $data['watermark']['appid'];
                        unset($data['watermark'],$data['openId'],$data['nickName'],$data['avatarUrl']);
                    }
                    $id= DB::table($this->table)->insertGetId($data);
                    $s_uid['s_uid'] = sha1('TZ_'.$id.'_applet');
                    $this->update(array('id'=>$id),$s_uid);

                    $user = $this->find($id);
                    $user->avatarurl = restHttp($user->avatarurl);
                    
                }else{  // 修改头像信息
                	$info['nickname']  = $data->nickName;
                	$info['gender']    = $data->gender;
                	$info['avatarurl'] = $data->avatarUrl;
                	$info['language']  = $data->language;
                	$info['city']      = isset($data->city) ? $data->city : '';
                	$info['province']  = isset($data->province) ? $data->province : '';
                	$info['country']   = isset($data->country) ? $data->country : '';
                    $this->update(array('id'=>$user->id),$info);
                    
                    $user->nickname  = $data->nickName;
                    $user->gender    = $data->gender;
                    $user->avatarurl = restHttp($data->avatarUrl);
                    
                }
                $cache = config('cache');
                Redis::setex('openid_'.$appid.'_'.$openid,$cache['cache_time'],json_encode($user));
            }else{
                $user = json_decode($redis_user);
            }
            $session3rd = $this->randomFromDev(16);
            
            $user->session3rd = $session3rd;
            Redis::setex($session3rd,86400,$openid . $sessionKey);
            
            return $user;
        }
        return false;
    }
    
    
    /**
     * 获取用户的openid
     */
    public function UserId($appId ="",$secret="",$code=""){
    	$user= WechatApi::getInstance()->setParams("appId",$appId)->setParams("secret",$secret)->setParams("code",$code)->getSessionKey();
    	if($user){
    		$openId = $user->openid;
    		$redis_user =  Redis::get('openid_'.$appId.'_'.$openId.'_openid');
    		if(!$redis_user){
    			$user = $this->FindOpenid($openId);
    			if(!$user){
    				$data['addtime']   = date("Y-m-d H:i:s",time());
    				$data['openid']    = $openId;
    				$data['appid']     = $appId;
    				
    				$id= DB::table($this->table)->insertGetId($data);
    				$user = $this->FindInfo($id);
    			}
    			$cache = config('cache');
    			Redis::setex('openid_'.$appId.'_'.$openId.'_openid',$cache['cache_time'],json_encode($user));
    			
    		}else{
    			$user = json_decode($redis_user);
    		}
    		return $user;
    	}
    	return false;
    }
    
    
    /**
     * 获取微信群信息（openGId）
     */
    public function WechatGroup($appId ="",$secret="",$code="",$data){
    	$user= WechatApi::getInstance()->setParams("appId",$appId)->setParams("secret",$secret)->setParams("code",$code)->getSessionKey();
    	if($user){
    		$pc = new WXBizDataCrypt($appId, $user->session_key);
    		$info = $pc->decryptData($data['encryptedData'], $data['iv'], $data );
    		if ($info['code'] !== 0) {
    			return array('errcode'=>ErrorCode::$EncryptDataNotMatch,'msg'=>"解密信息错误!!");
    		}
    		$openGId = $info['data']->openGId;
    		return $this->saveGroup($openGId,$appId);
    	}
    	return false;
    }
    
    
    /**
     * 保存微信群信息
     */
    private function saveGroup($openGId,$appId){
    	if($openGId && $appId){
    		$redis_group =  Redis::get('opengid_'.$appId.'_'.$openGId);
    		if(!$redis_group){
    			$group = $this->findOpnenGId($openGId);
    			if(!$group){
    				$data['addtime']   = date("Y-m-d H:i:s",time());
    				$data['open_gid']  = $openGId;
    				$data['appid']     = $appId;
    				
    				$id= DB::table($this->groupTable)->insertGetId($data);
    				$group = $this->findGroup($id);
    			}
    			$cache = config('cache');
    			Redis::setex('opengid_'.$appId.'_'.$openGId,$cache['cache_time'],json_encode($group));
    			
    		}else{
    			$group = json_decode($redis_group);
    		}
    		return $group;
    	}
    	return false;
    }


    /**
     * 获取小程序码
     */
    public function WxaCode($appId ="",$secret="",$data){

        return WechatApi::getInstance()->setParams("appId",$appId)->setParams("secret",$secret)->getWxaCode($data);
    }
    
    
    /**
     * 查询微信群信息
     * @param $uid
     * @return mixed
     */
    public function findOpnenGId($openGId){
    	$group = self::SelectDataSql("SqlAppletGroup",$this->groupTable)->find($openGId,false);
    	if($group){
    		return $group;
    	}
    	return false;
    }
    
    
    /**
     * 查询微信群信息
     * @param $uid
     * @return mixed
     */
    public function findGroup($uid){
    	$group = self::SelectDataSql("SqlAppletGroup",$this->groupTable)->find($uid,true);
    	if($group){
    		return $group;
    	}
    	return false;
    }
    
    /**
     * 查询用户信息
     * @param $uid
     * @return mixed
     */
    private function FindInfo($uid){
    	$user =  self::SelectDataSql("SqlAppletUser",$this->table)->find($uid,true,false);
    	if($user){
    		return $user;
    	}
    	return false;
    }
    
    /**
     * 查询用户信息
     * @param $openid
     * @return mixed
     */
    private function FindOpenid($openid){
    	$user = self::SelectDataSql("SqlAppletUser",$this->table)->find($openid,false,false);
    	if($user){
    		return $user;
    	}
    	return false;
    }

    /**
     * 查询用户信息
     * @param $uid
     * @return mixed
     */
    public function find($uid){
        $user =  self::SelectDataSql("SqlAppletUser",$this->table)->find($uid);
       return $this->setHeadimgurl($user);
    }

    /**
     * 查询用户信息
     * @param $openid
     * @return mixed
     */
    public function findOpnenId($openid){
        $user = self::SelectDataSql("SqlAppletUser",$this->table)->find($openid,false);
        return $this->setHeadimgurl($user);
    }

    
    /**
     * 设置头像是http还是https
     * @param obj $user
     * @return unknown|boolean
     */
   protected function setHeadimgurl($user){
   	    if($user){
        	$user->avatarurl = restHttp($user->avatarurl);
        	return $user;
        }
        return false;  
   }
   
	/**
	 * update user date
	 * @param unknown_type $where
	 * @param unknown_type $data
	 */
    public function update($where,$data){
         return self::SelectDataSql("SqlAppletUser",$this->table)->update($where,$data);
    }
    
    
    /**
     * 读取/dev/urandom获取随机数
     * @param $len
     * @return mixed|string
     */
    public function randomFromDev($len) {
    	$fp = @fopen('/dev/urandom','rb');
    	$result = '';
    	if ($fp !== FALSE) {
    		$result .= @fread($fp, $len);
    		@fclose($fp);
    	}
    	else
    	{
    		trigger_error('Can not open /dev/urandom.');
    	}
    	// convert from binary to string
    	$result = base64_encode($result);
    	// remove none url chars
    	$result = strtr($result, '+/', '-_');
    
    	return substr($result, 0, $len);
    }

}