<?php

use Illuminate\Http\Request;

$api = app('api.router');

// 私有接口
$api->version('v1',['protected' => true,'middleware' => 'jwt.api.auth'],function ($api) {

});


$api->version('v1', function ($api) {

    /**
     * 活动信息
     */
    $api->any('/wx/jssdk', 'App\Http\Controllers\Api\V1\Wx\ActivityController@jssdk');
    $api->any('/wx/share', 'App\Http\Controllers\Api\V1\Wx\ActivityController@share');
    $api->any('/wx/appid', 'App\Http\Controllers\Api\V1\Wx\ActivityController@getAppId');
});



$api->version('v2', function ($api) {   // 第二版本的      (小程序)
    $api->any('user/applet', 'App\Http\Controllers\Api\V2\Applet\UserController@getAppletUser');
    $api->any('user/defect/applet', 'App\Http\Controllers\Api\V2\Applet\UserController@getAppletDefectUser');


    //嘉仔电台小程序
    require "Routers/miniprogramme/jiazaidiantai.php";


});
