<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Schedules\PkTaskSchedule as PTS;
use App\Schedules\UserLogSchedule;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use App\Models\TaskDelivery;
use Auth, Log;
class TestFunctionController extends Controller
{
    private $sms;
    public function __construct(){
        //$this->sms = $sms;
    }
    public function getIndex() {
        return view('test');
    }
    public function postIndex(Request $request)
    {
        //Auth::login(User::find(82));
        $us = new UserLogSchedule();
        $us->setNowDate('2017-04-26');
        $us->run();
        return view('test', ['back'=>'提交成功']);
    }
    public function postIndex2(){
        return response()->json(['res'=>false, 'info'=>'']);
        $taskId = 198;
        $this->updateMultipleVoteLike($taskId);
        return response()->json(['res'=>true, 'info'=>'']);
    }
    private function updateMultipleVoteLike($taskId){
        $vote = Vote::where('resource_id', $taskId)
            ->where('type', Vote::MULTIPLE)->first();
        $alternatives = explode(';', $vote->alternatives);
        for($i=0; $i<100;$i++){
            $voteCount = mt_rand(0, count($alternatives));
            $result = '';
            for($j=0;$j<$voteCount;$j++){
                $index = mt_rand(0, count($alternatives)-1);
                $result .= ($alternatives[$index].';');
            }
            $vr = new VoteRecord;
            $vr->vote_id = $vote->id;
            $vr->user_id = $i+1;
            $vr->result = $result;
            $vr->save();
        }
        $vote->voted = $vote->target;
        $vote->save();
        
    }
    private function updateSingleVoteLike($taskId){
        $votes = Vote::where('resource_id', $taskId)
            ->where('type', Vote::SINGLE)->get();
        foreach($votes as $vote) {
            $j=0;
            for($i=1; $i<=5; $i++){
                $vr = new VoteRecord;
                $vr->vote_id = $vote->id;
                $vr->user_id = $i+$j;
                $vr->result = mt_rand(1,2)>1?'like':'unlike';
                $vr->save();
            }
            $j+=5;
        }

    }
    private function updateDeliveryLikeSum($taskId){
        $deliverys = TaskDelivery::where('task_id', $taskId)->get();
        foreach($deliverys as $delivery){
            $delivery->like_sum = mt_rand(0,20);
            $delivery->save();
        }
    }
}
