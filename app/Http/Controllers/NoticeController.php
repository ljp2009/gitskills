<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SysNotification;
use App\Models\UserPrivateLetter;
use App\Common\CommonUtils;
use App\Common\Image;
use Auth, DB;
class NoticeController extends Controller
{
    public function index($page = 0){
        return view('notice.noticehall', ['noticeCt'=>0, 'likeCt'=>0, 'commentCt'=>0, 'taskCt'=>0, 'page'=>$page]);
    }
    public function getNoticeList($type, $page, $tid=0){
        return view('notice.noticelist', ['type'=>$type, 'tid'=>$tid, 'page'=>$page, 'title'=>'标题区域']);
    }

    public function getNoticeListData($type, $from, $to, $tid=0){
        $typeCode = SysNotification::getCode($type);
        if($typeCode<0){
            return response()->json(['res'=>false, 'info'=>[]]);
        }
        $userId = Auth::id();
        $query  = SysNotification::where('user_id', $userId);
        if($type == 'task'){
            $query = $query->where('type','>=', $typeCode);
            if($tid > 0){
                $query = $query->where('resource_id',$tid);
            }
        }else{
            $query = $query->where('type', $typeCode);
        }
        $data = $query->orderBy('id', 'desc')->skip($from)->take($to-$from+1)->get();
        $funcStr = camel_case('get_'.$type.'_data_view');
        return $this->$funcStr($data);
    }

    private function getPublicDataView($data){
        return view('notice.partview.publicitem', ['data'=>$data]);
    }
    private function getLikeDataView($data){
        return view('notice.partview.likeitem', ['data'=>$data]);
    }
    private function getCommentDataView($data){
        return view('notice.partview.commentitem', ['data'=>$data]);
    }
    private function getTaskDataView($data){
        return view('notice.partview.taskitem', ['data'=>$data]);
    }
    private function getInviteDataView($data){
        return view('notice.partview.inviteitem', ['data'=>$data]);
    }

    public function deleteNotice(){
    }

    
    public function loadUserLetterData($from, $to){
        $users = Auth::user();
		$user_id = $users->id;
		$to = $to - $from +1;
		$privateList = DB::select(DB::raw("select max(id) as id, case when user_id=$user_id then send_id else user_id end as from_id from t_user_private_letter where (user_id=$user_id and send_id !=0) or send_id =$user_id 
							group by( case when user_id=$user_id then send_id else user_id end) order by max(id) desc limit $from, $to"));
		foreach ($privateList as $k => $v){
            if($v->from_id == 0){
                $from_user = new User;
                $from_user->display_name = '系统';
                $from_user->id = 0;
            }else{
                $from_user = User::findOrFail($v->from_id);
            }
			$letter = UserPrivateLetter::findOrFail($v->id);
			$variableArr = !empty($letter['variable']) ? json_decode($letter['variable'], true) : '';
			$privateList[$k]->link   = !empty($variableArr) ? $variableArr['link'] : '';
			$privateList[$k]->msg    = CommonUtils::readPrivateLetter($letter['msg'],$letter['type'],$letter['variable']);
			$privateList[$k]->time   = CommonUtils::dateFormatting(strtotime($letter['created_at']));
			$privateList[$k]->status = $letter['status'];
			$privateList[$k]->avatar =Image::makeImage($from_user['avatar']);
			$privateList[$k]->display_name = $from_user['display_name'];
			$privateList[$k]->to_user_id = $from_user['id'];
		}
		return view('partview.privateitem', array('models'=>$privateList));
    }
}
