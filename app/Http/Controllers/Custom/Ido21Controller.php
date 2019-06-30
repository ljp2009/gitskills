<?php

namespace App\Http\Controllers\Custom;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Dimension;
use App\Models\DimensionPublish;
use App\Models\CustomVote;
use App\Models\CustomVoteRecord;
use App\Models\CustomVoteRecordDetail;
use Auth, DB;
class Ido21Controller extends Controller
{
    const IDODIMCODE = 24;
    const IDOBATCHCODE = 'IDO21COSER';
    const BASEPOOL = '000';
    const CTRPOOL0 = '100';
    const CTRPOOL1 = '101';
    const CTRPOOL2 = '102';
    const CTRPOOL3 = '103';
    const BASEPOOL_TARGET = 3;
    const CTROOL_TARGET = 7;
    const VOTEBOX_SIZE = 6;
    const RESET_FLAG = 5;
    private $debugs = [];
    private $isFinish = true;
    private function writeDebug($msg){
        array_push($this->debugs, $msg);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $voteArr = $this->getTop10();
        return view('custom.idoindex', ['votes'=>$voteArr]);
    }
    public function getCover()
    {
        return view('custom.idocover');
    }
    public function getVote()
    {
        return view('custom.idovote');
    }
    public function getLoadVote(){

        $topVotes = $this->getTop10();
        return response()->json(['res'=>true, 'info'=>$topVotes, 'url'=>'/custom/ido21/voted']);

        $userId = Auth::check()?Auth::id():0;
        //检查是否有用户没有反馈的选票 
        $record = null;
        $votes = $this->getUserNotFeedBackVotes($userId, $record);
        if(is_null($votes)){
            $votes = $this->getRandomVote($userId);
            $record = null;
            if($userId > 0){// 用户登陆时，获取选票的时候直接更新
                $record = $this->createVoteRecord($votes, $userId);
            }
        }
        $voteArr = [];
        foreach($votes as $vote){
            $voteItem = [
                'code'      => $vote->id,
                //'url'       => $vote->targetObj->image[0]->getPath(1,'332h_221w_4e_255-255-255bgc'),
                'url'       => $vote->targetObj->image[0]->getPath(1,'280h_210w_1e|210x280-2rc'),
                'detailUrl' => $vote->targetObj->image[0]->getPath(),
                'name'      => $vote->targetObj->text.$vote->id
            ];
            array_push($voteArr, $voteItem);
        }
        return response()->json(['res'=>Auth::check(),
            'info'=>is_null($record)?0:$record->id,
            'votes'=>$voteArr,
            'debug'=>$this->debugs]);
    }
    
    public function postVote(Request $request){
        $topVotes = $this->getTop10();
        return response()->json(['res'=>true, 'info'=>$topVotes, 'url'=>'/custom/ido21/voted']);
        if(!Auth::check()){
            return response()->json(['res'=>false, 'info'=>'not_login', 'url'=>'/auth/login/001']);
        }
        $values = $request['values'];
        if(is_null($values)){
            $values = [];
        }
        $userId = Auth::id();
        $recordId = $request['vid']; 
        $userRecord = CustomVoteRecord::find($recordId);
        if(is_null($userRecord) || $userRecord->user_id != $userId){//并非用户的选票
            return response()->json(['res'=>false, 'info'=>'', 'url'=>'/custom/ido21/voted']);
        }
        $votesArr = $userRecord->getVotesArr();
        $updatedVotes = [];
        foreach($votesArr as $voteId=>$value){ //仅检查用户选票中的选项
            if(in_array($voteId, $values)){
                array_push($updatedVotes, $voteId.":1");
                $vote = CustomVote::find($voteId);
                if(!is_null($vote)){
                    $vote->liked += 1;
                    $vote->save();
                }
            }else{
                array_push($updatedVotes, $voteId.":0");
            }
        }
        $userRecord->is_feedback = 1;
        $userRecord->voted = implode(';', $updatedVotes);
        $userRecord->save();
        $topVotes = $this->getTop10();
        return response()->json(['res'=>true, 'info'=>$topVotes, 'url'=>'/custom/ido21/voted']);

        /* //分解开的投票记录，目前不做排除操作，为了节省资源，不记录这部分数据
        foreach($values as $value){
            $recordDetail = new CustomVoteRecordDetail;
            $recordDetail->user_id = Auth::id();
            $recordDetail->batch_code = self::IDOBATCHCODE;
            $recordDetail->record_id = $userRecord->id;
            $recordDetail->vote_id = $value;
            $recordDetail->save();
        }
        */
    }
    public function getVoted(){
        return view('custom.idovoted');
    }
    private function getRandomVote($userId = 0){
        $res = [];
        $baseRecord = CustomVoteRecord::where('batch_code', self::IDOBATCHCODE)->where('user_id', 0)->first();
        if(is_null($baseRecord)){
            $this->writeDebug('导入选票数据。');
            $this->implodeVote();
            $baseRecord = CustomVoteRecord::where('batch_code', self::IDOBATCHCODE)->where('user_id', 0)->first();
        }
        $voteCtFlag = ((int)$baseRecord->voted);
        $this->writeDebug('目前的选票状态：'.$voteCtFlag);
        if($voteCtFlag < self::RESET_FLAG){ //尚未处理完成初始选票
            $this->getBasePoolVotes($res);
            $this->writeDebug('获取到的基础数据：'.count($res));
            if(count($res) < self::VOTEBOX_SIZE){ //基础表数据不足,切换投票状态以及计算CTR
                $this->writeDebug('基础数据不足：'.count($res));
                if($voteCtFlag == 0 && count($res) > 0){ //并未初始化过CTR
                    $this->resetCTR();
                    $baseRecord->voted = '1'; //标记CTR已经初始完成
                    $baseRecord->save();
                }
                if(count($res) == 0){ //基础数据已经清空，不在需要检查基础数据
                    $this->resetCTR();
                    $baseRecord->voted = self::RESET_FLAG; //标记CTR已经初始完成,队列计数从reset_flag开始
                    $baseRecord->save();
                }
                $this->getCTRPoolVotes($res, self::VOTEBOX_SIZE-count($res));//从CTR库中获取指定的数据
            }
        }else{
            $voteCt = $voteCtFlag + 1;
            if($voteCt % self::RESET_FLAG == 0){
                $this->resetCTR();
            }
            $baseRecord->voted = $voteCt; //标记CTR已经初始完成
            $baseRecord->save();
            $this->getCTRPoolVotes($res, self::VOTEBOX_SIZE);//从CTR库中获取指定的数据
        }
        return $res ;
    }
    private function getUserNotFeedBackVotes($userId, &$userRecord){
        if($userId == 0) return null;
        $userRecord = CustomVoteRecord::where('batch_code', self::IDOBATCHCODE)
           ->where('user_id', $userId)
           ->where('is_feedback', 0)->first();
        if(is_null($userRecord)) return null;
        $votesArr = $userRecord->getVotesArr();
        $ids = array_keys($votesArr);
        $votes = CustomVote::whereIn('id', $ids)->get();
        return $votes;
    }
    private function getBasePoolVotes(&$res){
        $basePoolCt = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('pool_code', self::BASEPOOL)
            ->where('voted', '<', self::BASEPOOL_TARGET)
            ->count();
        $this->writeDebug('基础选票数量：'.$basePoolCt);
        if($basePoolCt == 0){
        }
        else if($basePoolCt < self::VOTEBOX_SIZE){// 基础库数量已经不足
            $votes = CustomVote::where('batch_code', self::IDOBATCHCODE)
                ->where('pool_code', self::BASEPOOL)
                ->where('voted', '<', self::BASEPOOL_TARGET)
                ->get();
            foreach($votes as $vote){
                array_push($res, $vote);
            }
        }else{
            $indexs = $this->getRandomIndexs($basePoolCt, self::VOTEBOX_SIZE);
            $this->writeDebug('基础选票随机Index：'.implode(',', $indexs));
            foreach($indexs as $index){
                $vote = CustomVote::where('batch_code', self::IDOBATCHCODE)
                    ->where('pool_code' , self::BASEPOOL)
                    ->where('voted', '<', self::BASEPOOL_TARGET)
                    ->skip($index)->take(1)->first();
                array_push($res, $vote);
            }
            $this->writeDebug('获取基础选票数：'.count($res));
            
        }
    }
    private function getCTRPoolVotes(&$res, $count){
        $ctr1_ct = 0;
        $ctr2_ct = 0;
        $ctr3_ct = 0;
        for($i=0;$i<$count;$i++){
            $f = rand(1,10);
            if($f <= 5){
                $ctr1_ct++;
            }else if($f > 5 && $f<=8){
                $ctr2_ct++;
            }else{
                $ctr3_ct++;
            }
        }
        $ct = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('pool_code' , self::CTRPOOL1)->count();
        $indexs = $this->getRandomIndexs($ct, $ctr1_ct);
        foreach($indexs as $index){
            $vote = CustomVote::where('batch_code', self::IDOBATCHCODE)
                ->where('pool_code' , self::CTRPOOL1)
                ->skip($index)->take(1)->first();
            array_push($res, $vote);
        }

        $ct = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('pool_code' , self::CTRPOOL2)->count();
        $indexs = $this->getRandomIndexs($ct, $ctr2_ct);
        foreach($indexs as $index){
            $vote = CustomVote::where('batch_code', self::IDOBATCHCODE)
                ->where('pool_code' , self::CTRPOOL2)
                ->skip($index)->take(1)->first();
            array_push($res, $vote);
        }

        $ct = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('pool_code' , self::CTRPOOL3)->count();
        $indexs = $this->getRandomIndexs($ct, $ctr3_ct);
        foreach($indexs as $index){
            $vote = CustomVote::where('batch_code', self::IDOBATCHCODE)
                ->where('pool_code' , self::CTRPOOL3)
                ->skip($index)->take(1)->first();
            array_push($res, $vote);
        }
    }
    private function createVoteRecord($votes, $userId){
        $voted = [];
        //更新备选选票，并默认为未选中状态
        foreach($votes as $vote){
            $vote->voted += 1;
            $vote->save();
            array_push($voted, $vote->id.':0');
        }
        //生成一条用户相关的记录
        $userRecord = new CustomVoteRecord;
        $userRecord->user_id = $userId;
        $userRecord->batch_code = self::IDOBATCHCODE;
        $userRecord->voted = implode(';', $voted);
        $userRecord->save();
        $codeIds = [];
        //所有的备选的记录曝光数+1
        return $userRecord;
    }
    private function implodeVote(){
        $dimPubs = DimensionPublish::where('dimension_id', self::IDODIMCODE)->get();
        foreach($dimPubs as $dp){
            $cv = new CustomVote;
            $cv->resource = 'dimension';
            $cv->resource_id = $dp->id;
            $cv->target = self::BASEPOOL_TARGET;
            $cv->voted = 0;
            $cv->liked = 0;
            $cv->pool_code = self::BASEPOOL;
            $cv->batch_code = self::IDOBATCHCODE;
            $cv->save();
        }
        $baseVoteRecord = new CustomVoteRecord;
        $baseVoteRecord->user_id = 0;
        $baseVoteRecord->batch_code = self::IDOBATCHCODE;
        $baseVoteRecord->voted = '0';
        $baseVoteRecord->save();
    }
    private function resetCTR(){
        $votes = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('voted','>=',self::BASEPOOL_TARGET)->get();
        $this->writeDebug('刷新resetCTR：'.count($votes));
        $queue = [];
        $objList = [];
        foreach($votes as $vote){
            $objList[$vote->id] = $vote;
            $queue[$vote->id] = $vote->liked/$vote->voted;
        }
        arsort($queue);
        $length = count($objList);
        $percent20 = (int)($length*0.2);
        $percent30 = (int)($length*0.3);
        $i=0;
        foreach($queue as $voteId=>$ctr){
            $i++;
            if($i<=$percent20){
                $objList[$voteId]->pool_code = self::CTRPOOL1;
            }else if($i <= ($percent20+$percent30)){
                $objList[$voteId]->pool_code = self::CTRPOOL2;
            }else{
                $objList[$voteId]->pool_code = self::CTRPOOL3;
            }
            $objList[$voteId]->save();
        }
    }
    private function getRandomIndexs($max, $count){
        if($max == 0){
            return [];
        }
        $arr = [];
        if($max > $count){
            $tmpArr = range(0, $max-1);
            shuffle($tmpArr);
            for($i=0;$i <$count;$i++){
                array_push($arr, $tmpArr[$i]);
            }
        }else{
            $arr = range(0, $max-1);
        }
        return $arr;
    }
    public function getTop10(){
        $votes = CustomVote::where('batch_code', self::IDOBATCHCODE)
            ->where('liked','>', '0')
            ->orderBy('liked', 'desc')
            ->take(10)->get();
        $voteArr = [];
        $i=0;
        foreach($votes as $vote){
            $i++;
            $imgs = $vote->targetObj->image;
            $header = count($imgs)>1?$imgs[1]:$imgs[0];
            $voteItem = [
                'index'     => $i,
                'code'      => $vote->id,
                'liked'     => $vote->liked,
                'header'    => $header->getPath(1,'80h_80w_1e|80x80-2rc'),
            ];
            array_push($voteArr, $voteItem);
        }
        return $voteArr;
    }
}
