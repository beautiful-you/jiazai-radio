<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/27
 * Time: 下午4:37
 */

namespace Costa92\Wechat\Services;


use Dingo\Api\Provider\ServiceProvider;

class BuildWechatService extends  ServiceProvider
{
    public function boot(){

    }

    public function register()
    {
        $this->app->bind('', function($app){
            return new YunpianSMSService();
        });
    }
}