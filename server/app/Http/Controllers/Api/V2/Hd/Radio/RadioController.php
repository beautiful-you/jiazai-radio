<?php

namespace App\Http\Controllers\Api\V2\Hd\Radio;

use App\Models\AppletHd\Radio\Radio;
use App\Models\AppletHd\Radio\Reports;
use App\Models\AppletHd\Radio\UserCollect;
use App\Models\AppletHd\Radio\UserBrowse;
use App\Models\AppletHd\Radio\RadioColumn;
use App\Models\AppletHd\Radio\Story;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\DB;

class RadioController extends BaseController
{
    public function __construct()
    {

    }
    
    
    /**
     * 查询电台信息
     * @param Request $request
     */
    public function getRadio(){
    	$data = Radio::all();
    	$id = $data[0]->id;
    	if(!$id){
    		return $this->noData();
    	}
    	$radio = Radio::WithOnly('pictures',['radio_id','radio_picture'])
    				  ->select('id')->where('id',$id)->get();
    	
    	$column = RadioColumn::where('radio_id',$id)->select('id','column_name','column_picture')->orderBy('paixu','asc')->orderBy('id','asc')->get();
    	
    	$reports = Reports::select('id','report_title','publish_time','listing_diagram')
    					  ->where('radio_id',$id)->where('status',1)->where('recommend',1)
    					  ->orderBy('publish_time','desc')->orderBy('id','desc')->get();
    	
    	return $this->json(array('fileHost'=>$this->getImageHost(),'radio'=>$radio,'column'=>$column,'reports'=>$reports));
    }
    
    
    /**
     * 查询电台栏目下相对应的报道
     * @param Request $request
     */
    public function getColumnReport(Request $request){
    	$column_id = $request->get('id');
    	if($column_id){
    		$navigation = RadioColumn::where('id',$column_id)->select('column_navigation')->first();
    		
    		$reports = DB::table('radio_reports')->join('radio_column_report_links','radio_reports.id','=','radio_column_report_links.report_id')
			    		->where('radio_column_report_links.column_id','=',$column_id)
			    		->select('radio_reports.id','radio_reports.report_title','radio_reports.publish_time','radio_reports.listing_diagram')
			    		->orderBy('radio_reports.publish_time','desc')->orderBy('radio_reports.id','desc')->get();
    		
    		return $this->json(array('fileHost'=>$this->getImageHost(),'navigation'=>$navigation,'reports'=>$reports));
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 查询电台报道详情
     * @param Request $request
     */
    public function getReport(Request $request){
    	$report_id = $request->get('report_id');
    	$type = $request->get('type');
    	if($report_id && isset($type)){
    		if($type == 0){
    			$reports = Reports::select('id')
					    			->where('status',1)->where('recommend',1)
					    			->orderBy('publish_time','desc')->orderBy('id','desc')
    								->get();

    			return $this->FindReport($reports,$report_id,'id',$type);
    			
    		}else{
    			$reports = DB::table('radio_reports')->join('radio_column_report_links','radio_reports.id','=','radio_column_report_links.report_id')
				    			->where('radio_column_report_links.column_id','=',$type)
				    			->select('radio_reports.id')
				    			->orderBy('radio_reports.publish_time','desc')->orderBy('radio_reports.id','desc')->get();
    			
    			return $this->FindReport($reports,$report_id,'id',$type);
    			
    		}
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 用户收藏
     * @param Request $request
     */
    public function collect(Request $request){
    	$report_id = $request->get('report_id');
    	$uid = $request->get('uid');
    	if($report_id && $uid){
    		$collection = UserCollect::where('x_uid',$uid)->where('report_id',$report_id)->first();
    		$report = Reports::where('id',$report_id)->select('id','collection')->first();
    		if(!$report){
    			return $this->noData();
    		}
    		if(!$collection){
    			$addtime = date("Y-m-d H:i:s",time());
    			$data = UserCollect::create(['x_uid'=>$uid,'report_id'=>$report_id,'type'=>1,'collect_time'=>time(),'addtime'=>$addtime]);
    			if(!$data){
    				return $this->errorJson('202','收藏失败!!');
    			}
    			$report->collection = $report->collection + 1;
    			if($report->save()){
    				return $this->json(array('status'=>1,'msg'=>'收藏成功!!'));
    			}
    			return $this->errorJson('202','更新报道收藏量失败!!');
    		}
    		if($collection->type == 1){
    			$collection->type = 0;
    			$report->collection = $report->collection - 1;
    			if($collection->save() && $report->save()){
    				return $this->json(array('status'=>0,'msg'=>'取消成功!!'));
    			}
    			return $this->errorJson('202','取消失败!!');
    			
    		}elseif($collection->type == 0){
    			$collection->type = 1;
    			$collection->collect_time = time();
    			$report->collection = $report->collection + 1;
    			if($collection->save() && $report->save()){
    				return $this->json(array('status'=>1,'msg'=>'收藏成功!!'));
    			}
    			return $this->errorJson('202','收藏失败!!');
    			
    		}
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 记录浏览量
     * @param Request $request
     */
    public function browse(Request $request){
    	$report_id = $request->get('report_id');
    	$uid = $request->get('uid');
    	if($report_id && $uid){
    		$browse = UserBrowse::where('x_uid',$uid)->where('report_id',$report_id)->first();
    		$report = Reports::where('id',$report_id)->select('id','page_view')->first();
    		if(!$report){
    			return $this->noData();
    		}
    		if(!$browse){
    			$addtime = date("Y-m-d H:i:s",time());
    			$data = UserBrowse::create(['x_uid'=>$uid,'report_id'=>$report_id,'browse_num'=>1,'browse_time'=>time(),'addtime'=>$addtime]);
    			if(!$data){
    				return $this->errorJson('202','添加浏览记录失败!!');
    			}
    			$report->page_view = $report->page_view + 1;
    			if($report->save()){
    				return $this->json(array('status'=>1,'msg'=>'添加浏览记录成功!!'));
    			}
    		}
    		$browse->browse_num = $browse->browse_num + 1;
    		$browse->browse_time = time();
    		$report->page_view = $report->page_view + 1;
    		if($browse->save() && $report->save()){
    			return $this->json(array('status'=>1,'msg'=>'更新浏览记录成功!!'));
    		}
    		return $this->errorJson('202','更新浏览记录失败!!');
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 我的收藏
     * @param Request $request
     */
    public function getMyCollect(Request $request){
    	$uid = $request->get('uid');
    	if($uid){
    		$collection = UserCollect::WithOnly('report',['id','report_title','publish_time','listing_diagram'])
    								 ->select('report_id')
    								 ->where('x_uid',$uid)->where('type',1)->orderBy('collect_time','desc')->get();
    		return $this->json(array('fileHost'=>$this->getImageHost(),'collection'=>$collection));
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 我的浏览历史
     * @param Request $request
     */
    public function getMyBrowse(Request $request){
    	$uid = $request->get('uid');
    	if($uid){
    		$browse = UserBrowse::WithOnly('report',['id','report_title','publish_time','listing_diagram'])
						    	->select('report_id')
						    	->where('x_uid',$uid)->orderBy('browse_time','desc')->get();
    		return $this->json(array('fileHost'=>$this->getImageHost(),'browse'=>$browse));
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 判断用户是否收藏该报道
     * @param Request $request
     */
    public function CollectStatus(Request $request){
    	$report_id = $request->get('report_id');
    	$uid = $request->get('uid');
    	if($report_id && $uid){
    		$collection = UserCollect::where('x_uid',$uid)->where('report_id',$report_id)->first();
    		if(!$collection){
    			return $this->json(array('status'=>0,'msg'=>'用户没有收藏过该报道!!!'));
    		}
    		if($collection->type == 1){
    			return $this->json(array('status'=>1,'msg'=>'用户已收藏!!!'));
    		}else{
    			return $this->json(array('status'=>0,'msg'=>'用户没有收藏该报道!!!'));
    		}
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 查看用户收藏或浏览过的报道
     * @param Request $request
     */
    public function getMyReport(Request $request){
    	$report_id = $request->get('report_id');
    	$uid = $request->get('uid');
    	$type = $request->get('type');
    	if($report_id && $uid && $type){
    		if($type == 1){
    			$collection = UserCollect::where('x_uid',$uid)->where('type',1)->select('report_id')->orderBy('collect_time','desc')->get();
    			return $this->FindReport($collection,$report_id); 
    			
    		}elseif($type == 2){
    			$browse = UserBrowse::where('x_uid',$uid)->select('report_id')->orderBy('browse_time','desc')->get();
    			return $this->FindReport($browse,$report_id);
    			
    		}
    	}
    	return $this->noParams();
    }
    
    
    /**
     * 查询报道的数据
     * @param Request $request
     */
    private function FindReport($data = "",$report_id = "",$field = "report_id",$type = ""){
    	$len = count($data);
    	if($len == 0){
    		return $this->noData();
    	}
    	foreach($data as $key=>$row){
    		if($report_id == $row->$field){
    			if($key - 1 < 0){
    				$k['pre_key'] = $len - 1;
    			}else{
    				$k['pre_key'] = $key - 1;
    			}
    			if($key + 1 == $len){
    				$k['next_key'] = 0;
    			}else{
    				$k['next_key'] = $key + 1;
    			}
    		}
    	}
    	if(!isset($k)){
    		return $this->errorJson('202','报道数据错误!!');
    	}
    	 
    	$report = Reports::select('id','report_title','detail_drawing','audio','content','author','anchor','publish_time','page_view')
    					 ->where('id',$report_id)->first();
    	
    	$user = DB::table('radio_user_browse')->join('applet_user','radio_user_browse.x_uid','=','applet_user.id')
	    			->where('radio_user_browse.report_id','=',$report_id)
	    			->select('applet_user.avatarurl')
	    			->orderBy('radio_user_browse.browse_time','desc')->take(4)->get();
    	 
    	return $this->json(array('fileHost'=>$this->getImageHost(),'report'=>$report,'pre'=>$data[$k['pre_key']]->$field,'next'=>$data[$k['next_key']]->$field,'type'=>$type,'user'=>$user));
    }
    
    
    /**
     * 发现的报道数据
     * @param Request $request
     */
    public function getFindReport(){
    	$report = Reports::select('id','report_title','find_picture','audio','content','author','anchor','publish_time','page_view')
    					->where('is_find',1)->orderBy(DB::raw('RAND()'))->take(1)->get();
    	return $this->json(array('fileHost'=>$this->getImageHost(),'report'=>$report));
    }
    
    
    /**
     * 获取故事的数据
     * @param Request $request
     */
    public function getStory(){
    	$story = Story::orderBy('paixu','asc')->orderBy('id','asc')->get();
    	return $this->json(array('fileHost'=>$this->getImageHost(),'story'=>$story));
    }
   
    
}
