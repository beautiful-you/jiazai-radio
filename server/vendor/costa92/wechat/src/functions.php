<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/21
 * Time: 下午4:43
 */

if (!function_exists("mkdir_file")){
    function mkdir_file($path){
        if($path){
            if(!file_exists($path)){
                mkdir($path,0777,true);
            }
        }
    }
}


//数组转对象

 function arrayToObject($e){
    if( gettype($e)!='array' ) return;
    foreach($e as $k=>$v){
        if( gettype($v)=='array' || getType($v)=='object' )
            $e[$k]=(object)arrayToObject($v);
    }
    return (object)$e;
}

//对象转数组
 function objectToArray($e){
    $e=(array)$e;
    foreach($e as $k=>$v){
        if( gettype($v)=='resource' ) return;
        if( gettype($v)=='object' || gettype($v)=='array' )
            $e[$k]=(array)objectToArray($v);
    }
    return $e;
}

/**
 * 数组转xml格式
 * @param $arr
 * @return string
 */
function arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
        if (is_numeric($val))
        {
            $xml.="<".$key.">".$val."</".$key.">";

        }
        else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}


/**
 * 反射
 * @param string $class
 * @param string $method
 * @return bool|mixed
 */
function reflection($class = "",$method="",$params=array()){
    if($class && $method){
        $reflectionMethod = new \ReflectionMethod($class,$method);
        return $reflectionMethod->invokeArgs(new $class,$params);
    }
    return false;
}

/**
 * 随机生16位数字符串
 * @param int $length
 * @return string
 */
function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}


/**
 * 主机与参数拼接
 * @param $toURL
 * @param $paras
 * @return null|string
 */
function genAllUrl($toURL, $paras) {
    $allUrl = null;
    if(null == $toURL){
        die("toURL is null");
    }
    if (strripos($toURL,"?") =="") {
        $allUrl = $toURL."?". $paras;
    }else {
        $allUrl = $toURL."&". $paras;
    }
    return trimall($allUrl);
// 	return $allUrl;
}

/***
 * 此函数可以去掉空格，及换行。
 */
function trimall($str){
	$qian=array(" ","　","\t","\n","\r");
	$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}


/**
 * 数组转换成字符格式
 * @param $paraMap
 * @param $urlencode
 * @return string
 */
function formatQueryParaMap($paraMap, $urlencode){
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v){
        if (null != $v && "null" != $v && "sign" != $k) {
            if($urlencode){
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
    }
    $reqPar="";
    if (strlen($buff) > 0) {
        $reqPar = substr($buff, 0, strlen($buff)-1);
    }
    return $reqPar;
}


function getDocCommentMethod($class="",$method = ""){
    if($method && $class){
        $reflection = new \ReflectionClass($class);
        $doc = $reflection->getDocComment ();
        preg_match_all('/@method '.$method.' (.*?)\n/',$doc,$rs);
        return preg_replace('# #','', $rs[1][0]);
    }
   return false;
}


function str_code($text,$rand){
    if($text && $rand){
        return str_replace("#code#",$rand,$text);
    }
    return false;
}

/**
 * 判断是什么请求
 * @return string
 */
function getQuere(){
    return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
}


function restHttp($url){
    if(getQuere() == "https://"){
        return  str_replace('http://',getQuere(),$url);
    }elseif(getQuere() == "http://"){
        return str_replace('https://',getQuere(),$url);
    }
    return $url;
}


function wx_return($code,$msg){
  return "<xml>
            <return_code><![CDATA[$code]]></return_code>
            <return_msg><![CDATA[$msg]]></return_msg>
          </xml>";
}

