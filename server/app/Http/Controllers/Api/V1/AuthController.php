<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Wx\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class AuthController extends BaseController
{


    public function store(){
        exit();
        $user= $this->getUser(6);
        var_dump(\Auth::attempt($user->toArray()));
//        $token = \Auth::attempt($user->toArray());
//        print_r($token);
    }


    public function upToken(Request $request){

        /**
         * token的有效值 redis
         */

        //刷新token值
//
//        $user = $this->getUserToken();
//        print_r($user);
//


        $jwtAuth = JWTAuth::setRequest($request);
        if (!$token = $jwtAuth->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }else{
            echo 11;
        }




//        $user = User::where(array('openid'=>$credentials))->first();
//        if($token = JWTAuth::attempt($user)){
//
//        };$event
//         JWTAuth::attempt($credentials);
    }

    public function user(){
        $credentials =  app("request")->get("openid");
        $token = $this->setToken($credentials);
        return $this->json(array('token'=>$token));
    }

    public function getToken(){
        $credentials= app('request')->only('openid');
        $user = User::where(array('openid'=>$credentials))->first();
//        if($token = JWTAuth::($user)){
    }



}
