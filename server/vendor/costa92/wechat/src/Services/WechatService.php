<?php

/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/16
 * Time: 下午1:16
 */
namespace Costa92\Wechat\Services;
use Costa92\Wechat\Https;
use Costa92\Wechat\Method\Attach;
use Costa92\Wechat\Method\Form;
use Costa92\Wechat\Method\OtherForm;
use Costa92\Wechat\Method\Help;
use Costa92\Wechat\Method\SeniorHelp;
use Costa92\Wechat\Method\Jssdk;
use Costa92\Wechat\Method\Red;
use Costa92\Wechat\Method\Shake;
use Costa92\Wechat\Method\Sms;
use Costa92\Wechat\Method\Voice;
use Costa92\Wechat\Method\Picture;
use Costa92\Wechat\Method\Photo;
use Costa92\Wechat\Method\PictureLink;
use Costa92\Wechat\Method\VoicoMatch;
use Costa92\Wechat\Method\Wechat;
use Costa92\Wechat\Method\Dafan;
use Costa92\Wechat\Method\Answer;
use Costa92\Wechat\Method\Applet;
use Costa92\Wechat\Method\AutoForm;
use Costa92\Wechat\Method\Visits;
use Costa92\Wechat\Method\UserShare;
use Costa92\Wechat\Method\NewHelp;
use Costa92\Wechat\Method\RestrictHelp;
use Costa92\Wechat\Method\LForm;
use Costa92\Wechat\Method\AlcoholRed;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;

class WechatService
{
    protected $_params = array();
    public function __construct(){

    }

    public function WechatMethod($method=""){
        if($method){
           return  reflection(Wechat::class,$method,$this->getParams());
        }
        return false;
    }

    public function JssdkMethod($method=""){
        if($method){
            return  reflection(Jssdk::class,$method,$this->getParams());
        }
        return false;
    }


    public function Form($method=""){
        if($method){
            return reflection(Form::class,$method,$this->getParams());
        }
        return false;
    }
    
    
    public function OtherForm($method=""){
    	if($method){
    		return reflection(OtherForm::class,$method,$this->getParams());
    	}
    	return false;
    }


    public function WechatRed($method=""){
        if($method){
            return  reflection(Red::class,$method,$this->getParams());
        }
        return false;
    }

    /**
     * 语音
     * @param string $method
     * @return bool|mixed
     */
    public function WechatVoice($method=""){
        if($method){
            return  reflection(Voice::class,$method,$this->getParams());
        }
        return false;
    }

    /**
     * 语音
     * @param string $method
     * @return bool|mixed
     */
    public function WechatVoiceMatch($method=""){
        if($method){
            return  reflection(VoicoMatch::class,$method,$this->getParams());
        }
        return false;
    }

    /**
     * 上传图片
     * @param string $method
     * @return bool|mixed
     */
    public function Attach($method=""){
        if($method){
            return  reflection(Attach::class,$method,$this->getParams());
        }
        return false;
    }


    /**
     *
     * 助力
     */
    public function WechatHelp($method = ""){
        if($method){
            return  reflection(Help::class,$method,$this->getParams());
        }
        return false;
    }

    /**
     * 短信
     * @param string $method
     * @return bool|mixed
     */
    public function WechatSms($method = ""){
        if($method){
            return  reflection(Sms::class,$method,$this->getParams());
        }
        return false;
    }


    /**
     * 摇一摇
     */

    public function WechatShake($method=""){
        if($method){
            return  reflection(Shake::class,$method,$this->getParams());
        }
        return false;
    }

    
    
    public function WechatPicture($method=""){
    	if($method){
    		return  reflection(Picture::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    
    
    public function WechatPictureLink($method=""){
    	if($method){
    		return  reflection(PictureLink::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    
    /**
     * 上传base64格式图片
     */
    public function WechatPhoto($method=""){
    	if($method){
    		return  reflection(Photo::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    
    /**
     * 高级助力
     */
    public function WechatSeniorHelp($method = ""){
    	if($method){
    		return  reflection(SeniorHelp::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    
    /**
     * 点餐
     */
    public function WechatDafan($method = ""){
    	if($method){
    		return  reflection(Dafan::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    
    /**
     * 答题
     */
    public function WechatAnswer($method = ""){
    	if($method){
    		return  reflection(Answer::class,$method,$this->getParams());
    	}
    	return false;
    }
    
    /**
     * 小程序
     */
    public function WechatApplet($method = ""){
    	if($method){
    		return  reflection(Applet::class,$method,$this->getParams());
    	}
    	return false;
    }
    

    
    public function AutoForm($method = ""){
    	if($method){
    		return  reflection(AutoForm::class,$method,$this->getParams());
    	}
    	return false;
    }


    public function Visits($method = ""){
        if($method){
            return  reflection(Visits::class,$method,$this->getParams());
        }
        return false;
    }


    public function UserShare($method = ""){
        if($method){
            return  reflection(UserShare::class,$method,$this->getParams());
        }
        return false;
    }


    public function NewHelp($method = ""){
        if($method){
            return  reflection(NewHelp::class,$method,$this->getParams());
        }
        return false;
    }


    public function RestrictHelp($method = ""){
        if($method){
            return  reflection(RestrictHelp::class,$method,$this->getParams());
        }
        return false;
    }


    public function LForm($method = ""){
        if($method){
            return  reflection(LForm::class,$method,$this->getParams());
        }
        return false;
    }


    /**
     *  酒水活动红包
     */
    public function AlcoholRed($method = ""){
        if($method){
            return  reflection(AlcoholRed::class,$method,$this->getParams());
        }
        return false;
    }


    /**
     * 设置参数
     * @param $params
     * @return $this|bool
     */
    public function setParams($params){
        if(is_array($params)){
            $this->_params = $params;
            return $this;
        }
        return false;
    }

    /**
     * 获取参数
     * @return array
     */
    public function getParams(){
        return $this->_params;
    }

}