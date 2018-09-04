<?php

namespace App\Admin\Controllers\AppletHd\Radio;

use App\Models\AppletHd\Radio\Radio;
use App\Models\AppletHd\Radio\Reports;
use App\Models\AppletHd\Radio\RadioColumn;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ReportController extends Controller
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

            $content->header('发表报道');
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
        return Admin::grid(Reports::class, function (Grid $grid) {
			
        	$grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();
            $grid->report_title('报道标题');
            $grid->listing_diagram('列表图')->image("",50,50);
            $grid->radio()->radio_name('属于电台');
            $grid->collection('收藏量');
            $grid->page_view('浏览量');
            $grid->publish_time('发表时间');
            $states = [
            0 => ['text' => 'NO'],
            1 => ['text' => 'YES'],
            ];
            
            $grid->column('开关')->switchGroup([
            		'recommend' => '是否推荐', 'is_find' => '发现' ,'status' => '是否可见'
            		], $states);
            $grid->filter(function($filter){
            	$filter->useModal();
            
            	$filter->like('report_title', '报道标题');
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
        return Admin::form(Reports::class, function (Form $form) {
        	$form->display('id', 'ID');
        	$form->text("report_title","报道标题")->placeholder("请输入报道标题");
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
        	$form->multipleSelect('radio_column','栏目显示')->options(RadioColumn::all()->pluck('column_name', 'id'));
        	$form->image("listing_diagram","报道列表图")->move('image/radio')->uniqueName();
        	$form->image("detail_drawing","报道详情页图")->move('image/radio')->uniqueName();
        	$form->file("audio","报道音频")->move('file/radio')->uniqueName();
        	$form->textarea("content","报道内容")->rows(9);
        	$form->switch('is_find','发现');
        	$form->image("find_picture","发现报道详情页图")->move('image/radio')->uniqueName();
        	$form->text("author","小编")->placeholder("请输入小编名字");
        	$form->text("anchor","主播")->placeholder("请输入主播名字");
        	$form->date("publish_time","发表时间")->format('YYYY.MM.DD');
        	$form->text("page_view","浏览量")->default(800);
        	$form->switch('recommend','是否推荐');
        	$form->switch('status','是否可见');
        	$form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At'); 
        });
    }
}
