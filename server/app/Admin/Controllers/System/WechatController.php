<?php

namespace App\Admin\Controllers\System;

use App\Models\System\Wechat;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WechatController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Wechat::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name();
            $grid->appid();
            $grid->app_mchid();
            $grid->secret();
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Wechat::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text("name","微信名称")->placeholder("请输入微信名称");
            $form->text("appid","微信账号")->placeholder("请输入微信AppId");
            $form->text("secret","微信秘钥")->placeholder("请输入微信秘钥");
            $form->text("app_mchid","商户号")->value("");
            $form->text("app_key","商户key")->value("");
            $form->textarea("apiclient_cert","证书cert")->value("输入证书cert");
            $form->textarea("apiclient_key","证书key")->value("输入证书key");
            $form->textarea("rootca","证书rootca")->value("输入证书");
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
