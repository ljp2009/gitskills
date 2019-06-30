<?php
/**
 * @date   2016-1-26
 * @author ViewsTap Programmer xiaocui * 记录用户的偏好
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Common\GoldManager as GM;
use App\Common\VoteHandler;
use App\Common\Image;
use Auth, Redirect, Input, DB;

class VoteController extends QueueController
{
	public static $GOLD = 5;
	public static $RESOURCE_SINGLE_VOTE = 'singleVote';
	public static $RESOURCE_MULTIPLE_VOTE = 'multipleVote';
	
    public function postVote(Request $request){
        $recordId = $request['voteId'];
        $userId = Auth::id();
        $values = $request['values'];
        $res = VoteHandler::updateUserVoteRecord($recordId, $userId, $values);
        return response()->json(['res'=>true]);
    }
    public function checkVote(){
        if(!Auth::check()){
            return response()->json(['res'=>false, 'info'=>'']);
        }
        $vote = VoteHandler::getVote(Auth::id());
        //$vote = $this->testData();
        if(is_null($vote)){
            return response()->json(['res'=>false, 'info'=>'']);
        }else{
            $voteRecord = VoteHandler::addUserVoteRecord($vote, Auth::id());
            $voteRes = $this->makeVoteArr($vote);
            $voteRes['id'] = $voteRecord->id;
            return response()->json(['res'=>true, 'vote'=>$voteRes]);
        }
    }
    private function makeVoteArr($vote){
        $res = [
            'type'  => $vote->getVoteType(),
            'id'    => $vote->id,
            'intro' => []
        ];
        $res['intro']['label'] = $vote->getReviewIntro();
        $res['intro']['img'  ] = $vote->getReviewImg()->getPath('1', '100h_80w_1e_1c');
        $res['items']          = [];

        $alternatives  = $vote->alternativeObjects;

        foreach($alternatives as $alternative){
            $arr = [
                'id'   => $alternative->id,
                'label' => $alternative->text,
                'text' => $alternative->text,
                'img' => $alternative->reviewImg->getPath('1', '400h_400w_4e_255-255-255bgc'),
            ];
            array_push($res['items'], $arr);
        }
        return $res;
    }
    private function testData(){
        $type = 'text';
        $ct = 2;
        $res = [
            'id'=>1,
            'type'=>$type,
            'intro'=>[
                'img'=>'http://img.umeiii.com/cover-1484292243-sM3XV0.jpg@100h_80w_1e_1c.jpg',
                'label'=>'本作女主角，一个普通的18岁高中生。她有着敏锐的观察力，热爱自拍、吐槽。5年后重回家乡的她选择在当地Blackwell Academy学院就读摄影专业。然而校园处处充满着霸凌，上课时Max也因自拍、无法回答出Mr.Jefferson的问题，受到同学Victoria的嘲笑。'
            ],
            'items'=>[]
        ];
        for($i=0;$i < $ct; $i++){
            array_push(
                $res['items'],
                [ 'id'=>$i,
                  'img'=>'http://img.umeiii.com/cover-1484292243-sM3XV0.jpg@400h_400w_4e_255-255-255bgc.jpg',
                  'label'=>'本作女主角，一个普通的18岁高中生。她有着敏锐的观察力，热爱自拍、吐槽。5年后重回家乡的她选择在当地Blackwell Academy学院就读摄影专业。然而校园处处充满着霸凌，上课时Max也因自拍、无法回答出Mr.Jefferson的问题，受到同学Victoria的嘲笑。',
                  'text'=>'本作女主角，一个普通的18岁高中生。她有着敏锐的观察力，热爱自拍、吐槽。5年后重回家乡的她选择在当地Blackwell Academy学院就读摄影专业。然而校园处处充满着霸凌，上课时Max也因自拍、无法回答出Mr.Jefferson的问题，受到同学Victoria的嘲笑。'
                ]
            );
        }
        return $res;
    }
}
?>
