<?php

namespace App\Admin\Controllers\AppletHd\Radio;

use App\Models\AppletHd\Radio\Radio;
use App\Models\AppletHd\Radio\Story;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class StoryController extends Controller
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

            $content->header('添加故事');
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
        return Admin::grid(Story::class, function (Grid $grid) {
			
        	$grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();
            $grid->story_title('故事标题');
            $grid->small_unchecked_picture('小图标')->image("",50,50);
            $grid->background_icon('图标背景')->image("",50,50);
            $grid->created_at('创建时间');
            $grid->updated_at('修改时间');
            $grid->filter(function($filter){
            	$filter->useModal();
            
            	$filter->like('story_title', '故事标题');
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
        return Admin::form(Story::class, function (Form $form) {
        	$form->display('id', 'ID');
        	$form->text("story_title","故事标题")->placeholder("请输入故事标题");
        	$form->text("story_anchor","故事主播")->placeholder("请输入主播名字");
        	$form->image("big_picture","大图标")->move('data/case/jiazai/image/radio')->uniqueName();
        	$form->image("small_picture","小图标")->move('data/case/jiazai/image/radio')->uniqueName();
        	$form->image("small_unchecked_picture","未选中小图标")->move('data/case/jiazai/image/radio')->uniqueName();
        	$form->image("background_icon","图标背景")->move('data/case/jiazai/image/radio')->uniqueName();
        	$form->image("background_unchecked_icon","未选中图标背景")->move('data/case/jiazai/image/radio')->uniqueName();
        	$form->file("story_audio","故事音频")->move('file/radio')->uniqueName();
        	$form->color("background_color","故事背景颜色")->default("#ff0000");
        	$form->text("paixu","排序")->default(0);
        	$form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At'); 
        });
    }
}
