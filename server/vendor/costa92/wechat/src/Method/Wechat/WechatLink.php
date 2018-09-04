<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/29
 * Time: 下午3:06
 */

namespace Costa92\Wechat\Method\Wechat;

/**
 * Class WechatLink
 * @package Costa92\Wechat\Method\Wechat
 *
 * @method GetTicket           /cgi-bin/ticket/getticket
 * @method AccessToken         /cgi-bin/token
 * @method OauthAccessToken    /sns/oauth2/access_token
 * @method UserInfo            /sns/userinfo
 * @method Upload              /cgi-bin/media/upload
 * @method Download            /cgi-bin/media/get
 * @method DeviceApplyId       /shakearound/device/applyid
 * @method DeviceApplyStatus   /shakearound/device/applystatus
 * @method JsCode2Session      /sns/jscode2session
 * @method GetWxaCode          /wxa/getwxacodeunlimit
 *
 */

class WechatLink
{
    private static $host ="https://api.weixin.qq.com";
    private static $file_host="http://file.api.weixin.qq.com";
    private static $data;
    public function __construct()
    {

    }

    /**
     *
     * @param string $method
     * @return bool|null|string
     */
    public static function getLink($method ="",$type=1){
        if($method){
            $methods = getDocCommentMethod(WechatLink::class,$method);
            if ($type == 1){
                $link = static::$host.$methods;
            }elseif ($type == 2 ){
                $link = static::$file_host.$methods;
            }else{
                $link ="";
            }
            return static::getValues($link);
        }
       return false;
    }

    /**
     * 设置参数
     * @param $data
     * @return mixed
     */
    public static function setParams($data){
        static::$data = $data;
        return self::class;
    }

    /*
     * 获取参数
     */
    public static function getParams(){
        return static::$data;
    }

    /**
     * 获取URL地址
     * @param string $link
     * @return bool|null|string
     */
    public static  function getValues($link=""){
        if(static::$data){
          $url = formatQueryParaMap(static::$data,false);
            return genAllUrl($link,$url);
        }
        return false;
    }
}