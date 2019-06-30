<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LongDiscussionController extends Controller
{
 	public function loadDiscussionList($resource, $type, $page, $pid){
		$view = view("common.discussionlist");
		$view->withResourcename($resource);
		$view->withResourceid($pid);
		$view->withIsshort($type==='short');
		$view->withPage($page);
		$view->withDeleteroute('/common/discuss/delete');
		return $view;
	}
    public function loadDiscussionDetail($page,$id){
		$item = $this->convertDimensionsPublishToListItem(LongDiscussion::find($id));
		return view('detailinfo',array('value'=>$item));
	}
	public function deleteLongDiscussion(){
		$id = Input::get('id');
		$model = LongDiscussion::find($id);
		$model->delete();
		echo CommonUtils::ajaxReturn(1);
	}
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
    private function loadPopularDiscussions($ipid, $from, $to){
        $idSums = Like::getLikeOrderList('long_discussion',['resource_id'=>$ipid], $from, $to);
        $idArr = [];
        $idSort = [];
        foreach($idSums as $sort=>$idSum){
            array_push($idArr, $idSum['id']);
            $idSort[$idSum['id']] = $sort;
        }
        $objs = LongDiscussion::whereIn('id', $idArr)->get();
        foreach($objs as $obj){
            $idSums[$idSort[$obj->id]]['obj'] = $obj;
        }
        return $idSums;
    }
}
