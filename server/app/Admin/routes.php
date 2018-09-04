<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
	
    $router->get('/', 'HomeController@index');
    $router->resource('wechat/users', Wx\UserController::class);
    $router->resource('wechat/system', System\WechatController::class);
    $router->resource('wechat/activity', Wx\ActivityController::class);


    /**
     * 电台
     */
    $router->resource('radio/found', AppletHd\Radio\FoundController::class);
    $router->resource('radio/report', AppletHd\Radio\ReportController::class);
    $router->resource('radio/column', AppletHd\Radio\ColumnController::class);
    $router->resource('radio/story', AppletHd\Radio\StoryController::class);


});
