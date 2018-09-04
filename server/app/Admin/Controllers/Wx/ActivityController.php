<?php

namespace App\Admin\Controllers\Wx;

use App\Models\System\Sms\Tpl;
use App\Models\System\Wechat;
use App\Models\Wx\Activity;

use Costa92\Wechat\Method\Sms\SmsLink;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CheckRow;

class ActivityController extends Controller
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
     *编辑接口
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
        return Admin::grid(Activity::class, function (Grid $grid) {
        	$grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();
            $grid->name("活动名称");
            $grid->sql_name("数据表名称");
            $grid->column("wechat_id",'属于微信')->display(function ($wechat_id){
                $rs =  Wechat::find($wechat_id);
                if($rs){
                    return $rs->name;
                }
            });
            $grid->share_title("分享标题");
            $grid->share_description("分享描述");
            $grid->share_img("分享图标")->image("",50,50);
             $grid->column("is_see",'可见')->display(function ($is_see){
                if($is_see == 1){
                    return '是';
                }else{
                    return '否';
                }
             });
            $grid->created_at();
            $grid->actions(function ($actions) {
                //配置指定红包
                $actions->prepend(new CheckRow($actions->getKey(),'/admin/act/appoint/red','A-RED'));
                //特殊红包配置
                $actions->prepend(new CheckRow($actions->getKey(),'/admin/wechat/act/red','RED'));
            });
            $grid->filter(function($filter){
            	$filter->useModal();

            	$filter->like('name', '活动名称');
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
        return Admin::form(Activity::class, function (Form $form) {
            $form->tab('Activity info', function ($form) {
                $form->hidden('id', 'ID');
                $form->text("name","活动名称")->placeholder("输入活动名称");
                $form->text("title","活动标题")->placeholder("输入活动标题");
                $form->select("wechat_id",'选择微信')->options(function ($id){
                    $list= Wechat::get();
                    if ($list) {
                        $arr = array();
                        foreach ($list as $row){
                            $arr[$row->id] = $row->name;
                        }
                        return $arr;
                    }
                });
                $form->select("pattern","授权模式")->options(['snsapi_userinfo'=>'用户模式', 'snsapi_base'=>'静默模式']);
                $form->textarea("share_title","分享标题")->rows(5);
                $form->textarea("share_description","分享描述")->rows(5);
                $form->image("share_img","分享图标")->move('image/wx/activity')->uniqueName();
                $form->select("sms_id",'选择短信')->options(function ($id){
                    $list= Tpl::where('check_status','SUCCESS')->get();
                    if ($list) {
                        $arr = array();
                        $arr[0] = "选择短信模板";
                        foreach ($list as $row){
                            $arr[$row->id] = $row->tpl_content;
                        }
                        return $arr;
                    }
                });
                $form->number("visits","初始访问量")->default(0);
                $form->datetime("start_time","活动开始时间")->format('YYYY-MM-DD HH:mm:ss');
                $form->datetime("end_time","活动结束时间")->format('YYYY-MM-DD HH:mm:ss');
                $form->radio('is_see','是否可见')->options(['0' => '否', '1'=> '是'])->default('1');
            })->tab('Activity Red Info',function ($form){
                $form->hasMany('red', function (Form\NestedForm $form) {
                    $form->text('nick_name','活动名称');
                    $form->text('send_name','红包提供方');
                    $form->text('send_activict','红包提供方');
                    $form->text('send_wishing',"红包祝福");
                    $form->text("send_cash",'红包最小值(单位:分)');
                    $form->text("send_max",'红包最大值(单位:分)');
                    $form->text('count','红包数量');
                });
            });

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saved(function (Form $form) {

                if ($form->id == null || $form->id =='null') {
                    if ($form->name != '') {
                        $activity = Activity::select('id')->where('name',$form->name)->orderBy('id','desc')->first();
                        Activity::where('id',$activity->id)->update(array('sql_name'=>'hd_'.$activity->id));
                    }
                } else {
                    if ($form->id > 190) {
                        Activity::where('id',$form->id)->update(array('sql_name'=>'hd_'.$form->id));
                    }
                }

            });

        });
    }
}
