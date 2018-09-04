<?php


namespace App\Http\Controllers\Api\V2;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *
 * @Resource（"test"）
 */class TextController extends Controller
{

/**
*显示所有用户
*
*获取所有注册用户的JSON表示。
*/public function index(){
         $data =array('success'=>true,'data'=>'222');
         return Response::json($data);
    }
    
    
    /**
     * 测试
     */
    public function user(Request $request){
    	$code =$request->get("code");
    	$rawData =$request->get("rawData");
    	$encryptedData =$request->get("encryptedData");
    	$iv =$request->get("iv");
    	$signature =$request->get("signature");
    	$userInfo =$request->get("userInfo");
    	print_r($code);
    	//print_r($rawData);
    	//print_r($encryptedData);
    	print_r($iv);
    	//print_r($signature);
    	//print_r($userInfo);
    	echo 11;
    }
}
