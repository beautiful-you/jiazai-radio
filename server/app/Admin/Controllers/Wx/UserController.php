<?php

namespace App\Admin\Controllers\Wx;

use App\Models\Wx\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\System\Wechat;

class UserController extends Controller
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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->nickname("昵称");
            $grid->column("sex",'性别')->display(function ($sex){
                $arr =[0=>'未知',2 => '女', 1=> '男'];
                return $arr[$sex];
            });

            $grid->headimgurl("头像")->image("",50,50);
            $grid->city("城市");
            $grid->province("省份");
            $grid->country("国家");
            $grid->appid("公众号")->display(function ($appid){
                $re = Wechat::where('appid',$appid)->first()->name;
                return $re;
            });
            $grid->addtime("创建时间");
            $grid->filter(function($filter){
                $filter->useModal();
                $filter->like('nickname', '昵称');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

//            $form->display('id', 'ID');
            $form->text("openid","openid")->placeholder("请输入opend");
            $form->text("nickname",'昵称')->placeholder("请输入昵称");
            $form->radio("sex","性别")->options([0=>'未知',1 => '女', 2=> '男'])->default("0");
            $form->image("headimgurl","头像");
            $form->text("city",'城市')->placeholder("请输入城市");
            $form->text("province",'省份');
            $form->text("country",'国家');
            $form->datetime("addtime","创建时间")->format('YYYY-MM-DD HH:mm:ss');
        });
    }
}
