<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\LongDiscussion;
use App\Http\Controllers\Common\CommonLikeController;
use App\Common\CommonUtils;
use App\Models\ListItem;
use App\Models\LikeModel;
use Auth, Redirect,Input;
class CommonDiscussionController extends Controller
{
	public static $REPLY_LIMIT_UNLOGIN = 2;
	public static $TEXT_COLLAPSE_LEN = 30;
	public static $REPLY_MOST_ITEMS = 10;

	protected $PAGNATE = 3;
	protected $RESOURCE_NAME = 'discussion';
	protected $RESOURCE_LONG_NAME = 'long_discussion';
	
	public static function getDiscussionCount($resource, $resourceId){
		return Discussion::where('resource', $resource)->where('resource_id', $resourceId)
						->count();
	}

	public static function getReplyCount($id){
		return Discussion::where('reference_id', $id)->count();
	}
	
	public static function findDiscussion($id){
		return Discussion::find($id);
	}

	public function loadDiscussionList($resource, $type, $page, $pid){
		$view = view("common.discussionlist");
		$view->withResourcename($resource);
		$view->withResourceid($pid);
		$view->withIsshort($type==='short');
		$view->withPage($page);
		$view->withDeleteroute('/common/discuss/delete');
		return $view;
	}

	public function loadDiscussionDetails($resource, $type, $from, $to, $pid){
		$isShort = ($type === 'short');
		return $this->loadDiscussions($resource, $pid, $isShort, 'full', $from, $to);
	}

	private function attachReplies($result){
		$query = Discussion::where('reference_id', $result->id)->orderBy('created_at', 'desc')->take(self::$REPLY_MOST_ITEMS);
		$result->replies = $query->get();
	}

	private function loadDiscussions($resource, $resourceid, $isShort, $mode, $from=0, $to=0){
		$likeController = new CommonLikeController;
		$type = ($isShort?0:1);
		$pag = $this->PAGNATE + 1;
		if($mode=='full'){
			$results = Discussion::where('resource', $resource)->where('resource_id', $resourceid)->where('type',$type);
			$results = CommonUtils::handleListDetails($results, $from, $to,false);
			if(sizeof($results)>0)
				$results = $likeController->attachLikes($this->RESOURCE_NAME, $results);
			foreach($results as $result){
				$this->attachReplies($result);
				$result->replyCount = self::getReplyCount($result->id);
			}
			$view = view("common.discussioncontent");
			$view->withIsshort($type);
			$view->withResults($results);
			$view->withHasmore(sizeof($results)>=$pag);
			$view->withResourcename($resource);
			$view->withResourceid($resourceid);
			$view->withDeleteroute('/common/discuss/delete');
			$lastid = 0;
			if(sizeof($results)>0){
				$lastid = $results[sizeof($results)-1]->id;
			}
			$view->withLastid($lastid);
			return $view;
		}else{
			$results = Discussion::where('resource', $resource)->where('resource_id', $resourceid)
						->where('type', $type);
            if($resource == 'dimension_publish'){
                $results = $results->orderBy('created_at')->take($pag)->get();
            }else{
                $results = $results->orderBy('created_at', 'desc')->take($pag)->get();
            }
			$results = $likeController->attachLikes($this->RESOURCE_NAME, $results);
			foreach($results as $result){
				$this->attachReplies($result);
				$result->replyCount = self::getReplyCount($result->id);
			}
			return $results;
		}
	}
	public static function getLongReplyCount($id){
		return LongDiscussion::where('reference_id', $id)->count();
	}
	private function attachLongReplies($result){
		$query = LongDiscussion::where('reference_id', $result->id)->orderBy('created_at', 'desc')->take(self::$REPLY_MOST_ITEMS);
		$result->replies = $query->get();
	}
	/**
	 * 长评论
	 * @param unknown $resource
	 * @param unknown $resourceid
	 * @param number $from
	 * @param number $to
	 * @return unknown
	 */
	private function loadLongDiscussions($resource, $resourceid, $from=0, $to=0){
		$likeController = new CommonLikeController;
		$pag = $this->PAGNATE + 1;
		$results = LongDiscussion::where('resource', $resource)->where('resource_id', $resourceid);
		$results = CommonUtils::handleListDetails($results, $from, $pag,false);
		if(sizeof($results)>0)
			$results = $likeController->attachLikes($this->RESOURCE_LONG_NAME, $results);
		foreach($results as $result){
			$this->attachLongReplies($result);
			$results->replyCount = self::getLongReplyCount($result->id);
		}
		return $results;
	}
   
	public function loadDiscussionNormal($resource, $id){
		$shortresults = $this->loadDiscussions($resource, $id, true, 'normal');
		
		$view = view("common.discussion");
		$view->withShortresults($shortresults);
		$route = '/common/discuss/delete';
		$view->withHasmoreshort(sizeof($shortresults)>$this->PAGNATE);
			//此处限定只有IP需要长评论
		if($resource == 'ip')
		{
			$route = '/common/discuss/deletelong';
			$longresults = $this->loadLongDiscussions($resource, $id);
			$view->withLongresults($longresults);
			$view->withHasmorelong(sizeof($longresults)>$this->PAGNATE);
		} 
		$view->withResourcename($resource);
		$view->withResourceid($id);
		$view->withRoute($route);
		return $view;
	}
	/**
	 * 长评论详情页
	 * @param unknown $id
	 */
	public function loadDiscussionDetail($page,$id){
// 		$longDiscuss = LongDiscussion::find($id);
// 		return view('detailinfo',array('value'=>$longDiscuss,'page'=>$page,'listName'=>'discussiondetail','pid'=>$id));
		$item = $this->convertDimensionsPublishToListItem(LongDiscussion::find($id));
		return view('detailinfo',array('value'=>$item));
	}
	private function convertDimensionsPublishToListItem($role)
	{
		if(!empty($role)){
			$item = new ListItem();
			$item->user = $role->user;
			$item->title = $role->title;
			$item->text = $role->text;
			$item->createAt = $role->created_at;
			$item->objectType = $this->RESOURCE_LONG_NAME;
			$item->objectId = $role->id;
			$item->linkUrl = $role->detailUrl;
			$query = LikeModel::with('user')
			->where('resource_id',$role->id)
			->where('resource',$this->RESOURCE_LONG_NAME);
			if(Auth::check())
			{
				$item->likeStatus = $query
				->where('user_id',Auth::user()->id)->count() >0?"2":1;
			}
			$item->likeCount = $query->count();
	
// 			$likes = $query->orderBy('created_at','desc')
// 			->take(7)->get();
	
// 			foreach ($likes as $key => $like) {
// 				array_push($item->likeUsers, $like->user);
// 			}
// 			$item->imageList = array_filter($role->imagePaths);
// 			$item->editUrl = "/dimension/publishedit/";
			return $item;
		}
		return array();
	}
	/**
	 * 显示回复
	 * @param unknown $resource
	 * @param unknown $pid
	 */
	public function loadNewestDiscussionReply($resource,$pid){
		$reply = Discussion::where('resource_id',$pid)->where('resource',$resource)->orderBy('created_at','desc')->take(3)->get();
		return view('partview.newestreply',array('models'=>$reply));
	}
	
	public function loadNewestDiscussionReplyList($from,$to,$pid){
		$likeController = new CommonLikeController;
		$reply = Discussion::where('response_id',$pid);
		$results = CommonUtils::handleListDetails($reply, $from, $to,false);
		if(sizeof($results)>0)
			$results = $likeController->attachLikes($this->RESOURCE_LONG_NAME, $results);
		foreach($results as $result){
			$this->attachReplies($result);
			$results->replyCount = self::getReplyCount($result->id);
		}
		return view('partview.replylist',array('models'=>$results));
	}
	/**
	 * 删除长评论
	 */
	public function deleteLongDiscussion(){
		$id = Input::get('id');
		$model = LongDiscussion::find($id);
		$model->delete();
		echo CommonUtils::ajaxReturn(1);
	}
	/**
	 * 创建长评论页面
	 * @param unknown $resource
	 * @param unknown $resourceID
	 * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
	 */
	public function displayCreateLongDiscussion($resource,$resourceID){
		$redirectUrl = $_SERVER['HTTP_REFERER'];
		return view('common.longdiscussioncreate', array('resourceID'=>$resourceID,
				'resource'=>$resource,'url'=>$redirectUrl));
	}
	/**
	 * 添加长评论
	 * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
	 */
	public function addLongDiscussion(){
		$obj = new LongDiscussion;
		$obj->user_id=Auth::user()->id;
		$obj->resource = Input::get('resource');
		$obj->resource_id = strval(Input::get('resourceId'));
		$obj->title = CommonUtils::escapeSpecialChars(Input::get('title'));
		$obj->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$obj->save();
		$redirect = Input::get('url');
		return redirect($redirect);
	}
	public function displayReplyCreationPage($responseId, $referenceId){
		$discussion = Discussion::find($responseId);
		$redirectUrl = $_SERVER['HTTP_REFERER'];
		return view('common.discussionreplycreate', array('discussion'=>$discussion, 
			'referenceId'=>$referenceId,'url'=>$redirectUrl));
	}

	public function displayReplyList($referenceId, $page){
		$discussion = Discussion::find($referenceId);
		return view('common.discussionreplylist', array('discussion'=>$discussion, 'page'=>$page,'deleteroute'=>'/common/discuss/delete'));
	}

	public function displayReplyContent($referenceId, $from, $to){
		$results = Discussion::where('reference_id', $referenceId);
		$results = CommonUtils::handleListDetails($results, $from, $to,true);	
		return view('common.discussionreplycontent', array('results'=>$results, 'referenceId'=>$referenceId));	
	}

	public function loadShortDiscussion($resource, $id){
		return $this->loadDiscussion($resource, $id, true, 'normal');
	}
	
	public function loadLongDiscussion($resource, $id){
		return $this->loadDiscussion($resource, $id, false, 'normal');
	}
	
	public function loadShortDiscussionFull($resource, $id){
		return $this->loadDiscussionFull($resource, $id, true);
	}
	
	public function loadLongDiscussionFull($resource, $id){
		return $this->loadDiscussionFull($resource, $id, false);
	}
	
	private function loadDiscussionFull($resource, $id, $isshort){
		$view = view("common.discussionfull");
		$view->withResourcename($resource);
		$view->withResourceid($id);
		$view->withIsshort($isshort);
		$view->withDeleteroute('/common/discuss/delete');
		return $view;
	}
	
	public function loadShortDisucssionFullContent($resource, $id, $rampid=0){
		return $this->loadDiscussion($resource, $id, true, 'full', $rampid);
	}
	
	public function loadLongDiscussionFullContent($resource, $id, $rampid=0){
		return $this->loadDiscussion($resource, $id, false, 'full', $rampid);
	}
	//@Deprecated
	private function composeJSON($results){
		$finalstr = '{"data":[';
		$ct = 0;
		$hasmore = (sizeof($results)>$this->PAGNATE);
		foreach($results as $result){
			$ct ++;
			if($ct>$PAGNATE){
				break;
			}
			$str = $result->toJson();
			$str = substr($str, 0, strlen($str)-1).','.'"user":'.$result->user->toJson();
			if(!empty($result->reference_id)){
				$str = $str.',"reference":'.$result->reference->toJson();
			}
			$str = $str.'}';
			if($ct < sizeof($results)&&$ct<$this->PAGNATE){
				$str = $str.',';
			}
			$finalstr = $finalstr.$str;
		}
		
		$finalstr = $finalstr.'], "hasmore":'.$hasmore.'}';
		
		return $finalstr;
	}
	

	private function loadDiscussion($resource, $id, $isShort, $mode, $rampid=0){
		$likeController = new CommonLikeController;
		
		$type = ($isShort?0:1);
		$pag = $this->PAGNATE + 1;
		if($mode=='full'){
			if($rampid==0){
				$result = Discussion::where('resource', $resource)->where('resource_id', $id)
						->where('type', $type)->orderBy('created_at', 'desc')->take($pag)->get();
			}else{
				$result = Discussion::where('resource', $resource)->where('resource_id', $id)
						->where('type', $type)->where('id', '<', $rampid)
						->orderBy('created_at', 'desc')->take($pag)->get();	
			}
			$result = $likeController->attachLikes($this->RESOURCE_NAME, $result);
			
			$view = view("common.discussioncontent");
			$view->withIsshort($type);
			$view->withResults($result);
			$view->withHasmore(sizeof($result)>=$pag);
			$view->withResourcename($resource);
			$view->withResourceid($id);
			$lastid = 0;
			if(sizeof($result)>0){
				$lastid = $result[sizeof($result)-1]->id;
			}
			$view->withLastid($lastid);
			return $view;
		}else{
			$result = Discussion::where('resource', $resource)->where('resource_id', $id)
						->where('type', $type)->orderBy('created_at', 'desc')->take($pag)->get();
						
			$result = $likeController->attachLikes($this->RESOURCE_NAME, $result);
			
			return $result;		
				
		}
		
	}

	public function addNewReply(){
		$obj = new Discussion;
		$obj->user_id=Auth::user()->id;
		$obj->response_id = strval(Input::get('responseTo'));
		$obj->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$obj->reference_id = strval(Input::get('referenceId'));
		$obj->save();
		
		$redirect = Input::get('url');
		return redirect($redirect);		
	}
	public function addNewShortDiscussion(){
		return $this->addNewDiscussion(true);
	}
	
	public function addNewLongDiscussion(){
		return $this->addNewDiscussion(false);
	}

	private function addNewDiscussion($isShort){
		$type = ($isShort?0:1);
		$obj = new Discussion;
		$obj->user_id=Auth::user()->id;
		$obj->resource = Input::get('resource');
		$obj->resource_id = strval(Input::get('resourceId'));
		$obj->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$obj->type= $type;
		$obj->save();
		$redirect = Input::get('url');
		$return = CommonUtils::ajaxReturn(1);
		echo $return;
	}
	
	public function updateDiscussion(){
		$id = intval(Input::get('discuss_id'));
		$obj = Discussion::find($id);
		$obj->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$obj->save();
		return Redirect::back();
	}
	
	public function displayCreateDiscussion($type, $resource, $id, $referenceid=0){
		$isshort = ($type==='short');
		$redirectUrl = $_SERVER['HTTP_REFERER'];
		$view = view("common.discussioncreate");
		$view->withResourcename($resource);
		$view->withResourceid($id);
		$view->withReferenceid($referenceid);
		$view->withIsshort($isshort);
		$view->withUrl($redirectUrl);
		return $view;
	}

	public function loadNewestDiscussion($resource, $pid)
	{
		$list = Discussion::with('User')
			->where('resource',$resource)
			->where('resource_id',$pid)
			->orderBy('created_at','desc')
			->take(3)->get();
		return view('partview.newestdiscussion',array('models'=>$list));
	}
	public function deleteDiscussion(){
		$id = Input::get('id');
		$model = Discussion::find($id);
		$model->delete();
		echo CommonUtils::ajaxReturn(1);
	}
	
	public function displayEditDiscussion($id){
		$model = Discussion::find($id);
		$redirectUrl = $_SERVER['HTTP_REFERER'];
		$view = view("common.discussionedit",array('model'=>$model,'url'=>$redirectUrl));
		return $view;
	}
	public function editShortDiscussion(){
		$id = Input::get('id');
		$redirect = Input::get('url');
		$model = Discussion::find($id);
		$model->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$model->save();
		return redirect($redirect);
	}
	
	public function loadLongDiscussionList($resource,$page,$pid){
		return view('discussionlist',array('page'=>$page,'pid'=>$pid,'listName'=>$resource));
	}
	public function loadLongDiscussionDetails($from,$to,$pid){
		$likeController = new CommonLikeController;
		$result = LongDiscussion::where('resource','ip')->where('resource_id',$pid);
		$results = CommonUtils::handleListDetails($result, $from, $to,false);
		foreach($results as $k=>$v){
			$query = LikeModel::with('user')
			->where('resource_id',$v->id)
			->where('resource',$this->RESOURCE_LONG_NAME);
			if(Auth::check())
			{
				$v->likeStatus = $query
				->where('user_id',Auth::user()->id)->count() >0?"2":1;
			}
		}
		
		return view('common.longdiscussioncontent',array('results'=>$results));
	}
}
?>
