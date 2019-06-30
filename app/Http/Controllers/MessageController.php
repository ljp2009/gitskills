<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPrivateLetter;
use App\Common\Image;
use App\Common\CommonUtils;
use DB, Auth;
class MessageController extends Controller
{
    public function showMessagePage($page, $title=''){ }
    public function showMessageData($from, $to, $title=''){ }
    public function showChatPage($userId, $title=''){
        return view('privatelistdialog', ['userId'=>$userId]);
    }
    public function loadHistoryMessage($userId, $from, $msgCt, $title=''){
		$ownerId  = Auth::id();
        $targetId = $userId; 

        $sql = '';
        $sql .= "select * from t_user_private_letter ";
        $sql .= "where ($from = 0 or id < $from) ";
        $sql .= "and ((user_id = $ownerId and send_id = $targetId) ";
        $sql .= "or  (user_id = $targetId and send_id = $ownerId))  ";
        $sql .= "order by id desc limit 0, $msgCt ";
        $privateList = DB::select(DB::raw($sql));
		foreach ($privateList as $k => $value) {
            if($value->send_id == 0){
                $user = new User;
                $user->display_name = '系统';
                $user->id           = 0;
            }else{
                $user = User::findOrFail($value->send_id);
            }
			$privateList[$k]->display_name = $user['display_name'];
			$privateList[$k]->avatar       = Image::makeImage($user['avatar']);
			$privateList[$k]->time         = CommonUtils::dateFormatting(strtotime($privateList[$k]->created_at));
			$privateList[$k]->isOwner      = (Auth::id() == $value->send_id);
			$privateList[$k]->formatMsg    = CommonUtils::readPrivateLetter($value->msg,$value->type,$value->variable);
		}
		return view('partview.privatedialogitem', array('models'=>$privateList));
    }
    public function addNewMessage(Request $request){
        $userId = Auth::id();
        $targetId = $request['userId'];
        $msg = $request['msg'];
		$letter = UserPrivateLetter::create([
				'user_id' => $targetId,
				'send_id' => $userId,
				'msg'     => $msg,
				'status'  => 'N'
				]);
        
        $user = User::findOrFail($letter->send_id);
        $letter->display_name = $user->display_name;
        $letter->avatar       = $user->avatar;
        $letter->time         = CommonUtils::dateFormatting(strtotime($letter->created_at));
        $letter->isOwner      = (Auth::id() == $letter->send_id);
        
        $html = (string)view('partview.privatedialogitem', array('models'=>[$letter]));
		return response()->json(['res'=>true, 'html'=>$html]);
    }
}
