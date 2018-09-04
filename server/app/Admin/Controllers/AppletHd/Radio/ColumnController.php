<?php

namespace App\Admin\Controllers\AppletHd\Radio;

use App\Models\AppletHd\Radio\Radio;
use App\Models\AppletHd\Radio\RadioColumn;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ColumnController extends Controller
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

            $content->header('创建电台栏目');
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
        return Admin::grid(RadioColumn::class, function (Grid $grid) {
			
            $grid->id('ID')->sortable();
            $grid->column_name('栏目名称');
            $grid->column_picture('栏目图标')->image("",50,50);
            $grid->radio()->radio_name('属于电台');
            $grid->created_at('创建时间');
            $grid->updated_at('修改时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RadioColumn::class, function (Form $form) {
        	$form->display('id', 'ID');
        	$form->text("column_name","栏目名称")->placeholder("请输入栏目名称");
        	$form->select("radio_id",'选择电台')->options(function ($id){
        		$list= Radio::get();
        		if ($list) {
        			$arr = array();
        			foreach ($list as $row){
        				$arr[$row->id] = $row->radio_name;
        			}
        			return $arr;
        		}
        	});
        	$form->text("column_navigation","栏目导航名称");
        	$form->image("column_picture","栏目图标")->move('image/radio')->uniqueName();
        	$form->text("paixu","排序")->default(0);
        	$form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At'); 
        });
    }
}
