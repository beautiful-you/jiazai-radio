<?php

/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/28
 * Time: 上午11:00
 */
namespace Costa92\Wechat\Method\Wechat;
use Costa92\Wechat\Method\Upload;
use Illuminate\Support\Facades\Redis as Redis;
use Costa92\Wechat\Https;

class WechatApi
{
    private $appArray=array();
    private $_valid_time=3600;
    protected $rs ="";
    public function __construct(){

    }

    static $instance;
    public static function  getInstance(){
        if( self::$instance==null ){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function setParams($key,$valve){
        $this->appArray[$key] = $valve;
        return $this;
    }

    public function getParams($key){
        return $this->appArray[$key];
    }
    /**
     *  获取 jsApiTicket
     *
     */
   public function getJsApiTicket(){
        $data= Redis::get('jsapi_ticket'.$this->getParams('appId'));
        if (!$data) {
           $accessToken = $this->getAccessToken();
           // 如果是企业号用以下 URL 获取 ticket
           // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
//           $url = $this->host."cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
           $url = $this->getUrl("GetTicket",array("type"=>"jsapi","access_token"=>$accessToken));

           $res = json_decode(Https::getInstance()->httpGet($url));

           $ticket = $res->ticket;
           if ($ticket) {
               Redis::setex("jsapi_ticket".$this->getParams('appId'),$this->_valid_time,$ticket) ;
           }
       } else {
           $ticket = $data;
       }

       return $ticket;
   }

    /**
     * 获取微信jssdk的Token值
     * @return mixed
     */
    public function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
//    $data = json_decode(file_get_contents(".json"));
        $data= Redis::get('access_token_red'.$this->getParams('appId'));
        if (!$data) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
//            $url = $this->host."cgi-bin/token?grant_type=client_credential&appid=".$this->getParams('appId')."&secret=".$this->getParams('secret');
            $url = $this->getUrl("AccessToken",array(
                "grant_type"=>"client_credential",
                "appid"=>$this->getParams('appId'),
                "secret"=>$this->getParams('secret'),
            ));
            $res = json_decode(Https::getInstance()->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                Redis::setex("access_token_red".$this->getParams('appId'),$this->_valid_time,$access_token) ;
            }
        } else {
            $access_token = $data;
        }
        return $access_token;
    }


    /**
     * 获取网页授权的token值
     * @return $this
     */
    public function getOauthAccessToken(){
//        $url =$this->host."sns/oauth2/access_token?appid=".$this->getParams('appId')."&secret=".$this->getParams('secret').'&code='.$this->getParams('code')."&grant_type=authorization_code";
        $url = $this->getUrl("OauthAccessToken",array(
            'appid'=>$this->getParams('appId'),
            'secret'=>$this->getParams('secret'),
            'code'=>$this->getParams('code'),
            'grant_type'=>"authorization_code",
        ));
        $this->rs=json_decode(Https::getInstance()->httpGet($url));
        return $this;
    }

    /**
     * 上传资源文件
     * @return mixed
     */

    public function Upload(){
        $url = $this->getUrl("Upload",array(
            'access_token'=>$this->getAccessToken(),
            'type'=>$this->getParams('type'),
            'media'=>$this->getParams('media'),
        ),2);
       return $this->rs=json_decode(Https::getInstance()->httpGet($url));
    }

    /**
     * 下载资源文件
     * @return mixed
     */

    public function Download(){
        $url = $this->getUrl("Download",array(
            'access_token'=>$this->getAccessToken(),
            'media_id'=>$this->getParams('media_id'),
        ),1);
        $rs= Https::getInstance()->httpGet($url);
        $error = json_decode($rs);
        if(!isset($error->errcode)){
             $upload = new Upload();
            return $upload->setParams("media_id",$this->getParams('media_id'))->setParams('voice',$rs)->SaveVoiceQiniu();
        }
        return false;
    }


    public function DownloadAndUpload(){
        $url = $this->getUrl("Download",array(
            'access_token'=>$this->getAccessToken(),
            'media_id'=>$this->getParams('media_id'),
        ),1);
        $rs= Https::getInstance()->httpGet($url);
        $error = json_decode($rs);
        if(!isset($error->errcode)){
             $upload = new Upload();
            return $upload->setParams("media_id",$this->getParams('media_id'))->setParams('voice',$rs)->SaveVoiceToQiniu();
        }
        return false;
    }



    /**
     * 获取微信用户的信息
     * @return bool|mixed
     */
    public function getUserInfo(){
        if($this->rs && empty($this->rs->errcode)){
//            $url = $this->host.'sns/userinfo?access_token='..'&openid='.$this->rs->openid.'&lang=zh_CN';
            $url = $this->getUrl("UserInfo",array(
                    'access_token'=>$this->rs->access_token,
                    'openid'=>$this->rs->openid,
                    'lang'=>"zh_CN",
                ));
            $rs=json_decode(Https::getInstance()->httpGet($url));
            return $rs;
        }
        return false;
    }


    /**
     * 申请设备ID
     */
    public function applyId(){
        $url = $this->getUrl('DeviceApplyId',array(
            'access_token'=>$this->getAccessToken(),
        ));
        return json_decode(Https::getInstance()->httpGet($url));
    }

    /**
     *  查询设备ID申请审核状态
     */
    public function applyStatus(){
        $url = $this->getUrl('DeviceApplyStatus',array(
            'access_token'=>$this->getAccessToken(),
            'apply_id'=>$this->getParams('apply_id'),
        ));
        return json_decode(Https::getInstance()->httpGet($url));
    }
    
    /**
     * 获取用户的openid和session_key (小程序)
     * @return bool|mixed
     */
    public function getSessionKey(){
    	$url = $this->getUrl('JsCode2Session',array(
    		'appid'=>$this->getParams('appId'),
    		'secret'=>$this->getParams('secret'),
    		'js_code'=>$this->getParams('code'),
    		'grant_type'=>"authorization_code"
    	));
    	$rs = json_decode(Https::getInstance()->httpGet($url));
    	if(!isset($rs->errcode)){
    		return $rs;
    	}
    	return false;
    }


    /**
     * 获取小程序码 (小程序)
     * @return bool|mixed
     */
    public function getWxaCode($data){

        $accessToken = $this->getAccessToken();

        $url = $this->getUrl("GetWxaCode",array("access_token"=>$accessToken));
        $post_data = json_encode($data);
        $res = Https::getInstance()->http_post($url,$post_data);
        $error = json_decode($res);

        if(!isset($error->errcode)){
            $upload = new Upload();
            $path = 'wxa/code/';
            $link = $upload->setParams('image',$res)->SaveImageQiniu($path);
            if($link){
                return $link;
            }
        }
        return false;
    }

    /**
     * 获取请求微信API的地址
     * @param string $method
     * @param array $data
     * @return mixed
     */
    private function getUrl($method = " ",$data =array(),$type =1){
        $rs = WechatLink::setParams($data);
        return $rs::getLink($method,$type);
    }


}