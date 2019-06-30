<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Common\Notifaction;
use App\Http\Controllers\Common\CommonLikeController as LC;

use Input,Auth;
class DiscussionController extends Controller
{
    public function publish(){
        $userId = Auth::user()->id;
        $resource = Input::get('resource');
        $resourceId = Input::get('resourceId');
        $text = Input::get('text');
        $reference = Input::get('referenceId');

        $discussion = new Discussion;
        $discussion->user_id = $userId;
        $discussion->resource = $resource;
        $discussion->resource_id = $resourceId;
        $discussion->text = $text;
        if(!empty($reference) && $reference>0){
            $ref = Discussion::find($reference);
            if(!is_null($ref)){
                $discussion->reference_id = $ref->id;
                $discussion->response_id = $ref->user_id;
            }
        }
        $discussion->save();
        Notifaction::Notice(Notifaction::DISCUSSION, Auth::user(), $resource, $resourceId);
        return response()->json(['res'=>true]);
    }
    public function delete(Request $request){
        $userId = Auth::user()->id;
        $id = $request['id'];
        $discussion = Discussion::find($id);
        if(is_null($discussion)){
            return response()->json(['res'=>false, 'info'=>'notfind']);
        }
        if($userId != $discussion->user_id && Auth::user()->role != 'admin'){
            return response()->json(['res'=>false, 'info'=>'notowner']);
        }
        $discussion->delete();
        return response()->json(['res'=>true]);
    }
    public function count(){
        $resource = Input::get('resource');
        $resourceId = Input::get('resourceId');
        $cc =  Discussion::countDiscuss($resource, $resourceId);
        return response()->json(['res' => true, 'info' => $cc]);
    }
    public function getDiscussionPartview($type, $resource, $resourceId){
        $popularItems = [];
        if(in_array($type,['popular', 'all'])){
            $popularItems = Discussion::where('resource', $resource)->where('type',0)
                ->where('resource_id',$resourceId)
                ->where('like_sum','>',0)
                ->orderBy('like_sum','desc')
                ->take(3)->get();
        }
        $newestItems = [];
        if(in_array($type,['newest', 'all'])){
            $newestItems = Discussion::where('resource', $resource)->where('type',0)
                ->where('resource_id',$resourceId)
                ->orderBy('created_at','desc')
                ->take(5)->get();
        }
        return view('partview.discuss', ['newest'=>$newestItems, 'popular'=>$popularItems, 'resource'=>$resource, 'resource_id'=>$resourceId]);
    }
    public function getDiscussionList($type, $resource, $resourceId, $page=0){
        $title = ($type=='pop'?'热门评论':'最新评论');
        return view('discussionlist', [
            'title' => $title,
            'type' => $type,
            'resource' => $resource,
            'id' => $resourceId,
            'page' => $page,
        ]);
    }
    public function getDiscussionListData($type, $resource, $resourceId, $from, $to){
        if($type == 'popular'){
            //少获取一条数据，为了隐藏翻页
            return $this->getPopularData($resource, $resourceId, $from, $to-1);
        }
        if($type == 'newest'){
            return $this->getNewestData($resource, $resourceId, $from, $to);
        }
    }
    private function getNewestData($resource, $resourceId, $from, $to){
        $items = Discussion::where('resource', $resource)
            ->where('resource_id',$resourceId)
            ->orderBy('created_at', 'desc')
            ->skip($from)->take($to-$from+1)->get();
        return view('partview.discussitem', ['items'=>$items]);
    }
    private function getPopularData($resource, $resourceId, $from, $to){
        $items = Discussion::where('resource', $resource)
            ->where('resource_id',$resourceId)
            ->where('like_sum','>',0)
            ->orderBy('like_sum', 'desc')
            ->skip($from)->take($to-$from+1)->get();
        return view('partview.discussitem', ['items'=>$items]);
    }

}
