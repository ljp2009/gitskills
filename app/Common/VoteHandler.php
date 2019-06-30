<?php
namespace App\Common;
use Config;
use App\Models\Vote;
use App\Models\VoteRecord;
use Auth, DB;
class VoteHandler
{
    const SINGLEVOTE = 1;
    const MULTIPLEVOTE = 9;
    const CHECKVOTEDAYS = 2; //检查用户投票时候获取投票的时间范围，默认2天
    const CHECKVOTEHOURS = 2; //检查用户最近投票时候的小时数，默认2小时
    const CANCELFLAG = 5; //用户在随机抽取选票时，如果5次都抽到了已经满员的选票（多用户并发），则放弃这次抽取过程。
    /*
     * 为指定的用户获取一张可用的选票
    * */
    public static function getVote($userId){
        //获取用户两天内投过的选票(为了去除重复的选票）
        $twoDaysAgo = strtotime('-'.self::CHECKVOTEDAYS.' days');
        $userRecords = VoteRecord::where('user_id', $userId)
            ->where('created_at', '>', $twoDaysAgo)
            ->orderBy('id', 'desc')->take(8)->get();

        if(count($userRecords) != 0){
            $newestRecord = $userRecords[0];
            //2个小时内这个用户领取过选票(避免用户频繁获取到选票）
            $someHoursAgo = strtotime('-'.self::CHECKVOTEHOURS.' hours');
            if(strtotime($newestRecord->created_at) > $someHoursAgo){
                return null;
            }
            $userRecordIds = [];
            foreach($userRecords as $ur){
              array_push($userRecordIds, $ur->vote_id);
            }
        }
        //设定一个循环次数，如果用户5次还没能选到符合条件的选票， 直接认为无选票可用, 避免长时间无响应
        //此处循环抓区投票数据,保证拿到最终有效的结果
        $lockFlag = self::CANCELFLAG;
        while($lockFlag>0){
            $selectedVote = null;
            $query =Vote::whereRaw('target > voted');
            //排除两天内用户已经拿到过的选票
            if(isset($userRecordIds)){
                $query  = $query->whereNotIn('id',$userRecordIds);
            }
            $query =$query->orderBy('batch') ->orderBy('voted')->take(10);
            $votes = $query->get();
            if(count($votes) == 0) return null; //没有符合条件的选票
            //为了尽量减少重复，随机从最多10个结果中找出一个符合条件的
            //
            $randomNum = mt_rand(0, count($votes)-1);
            $selectedVote = $votes[$randomNum];
            $ct = DB::table('t_vote')
                ->where('id', $selectedVote->id)
                ->whereRaw('target > voted')
                ->increment('voted');
            //成功执行了更新，当前选票有效
            if($ct = 1){
                return $selectedVote;
            }
            $lockFlag--;
        }
        return null;
    }
    /*
     * 为指定用户生成一个投票记录,
    * */
    public static function addUserVoteRecord($vote, $userId){
        //检查用户是否已经有了选票
        $vr = new VoteRecord;
        $vr->user_id = $userId;
        $vr->vote_id = $vote->id;
        $vr->result = '';
        $vr->save();
        $vote->voted += 1;
        $vote->save();
        return $vr;
    }
    /*
     * 更新用户选票记录的结果, 如果没有指定跳过检查的话哦，仅可以更新5分钟之内生成的用户投票记录
    * */
    public static function updateUserVoteRecord($recordId, $userId, $values, $unCheck=false){
        $query = VoteRecord::where('user_id', $userId)
            ->where('id', $recordId);
        if(!$unCheck){
            //五分钟内创建的符合投票条件的记录
            $query = $query->where('created_at','>', date('Y-m-d H:i:s' ,strtotime('-5 minutes')));
        }
        $voteRecord = $query->first();
        if(is_null($voteRecord)){
            return ['res'=>false, 'info'=>'timeout'];
        }
        $vote = $voteRecord->vote;
        $fValues = [];
        $alternativeIds = explode(';',$vote->alternatives);
        if(is_null($values)) $values = [];
        foreach($values as $v){
            if(in_array($v, $alternativeIds)){
               array_push($fValues, $v);
            }
        }
        $voteRecord->result = implode(';', $fValues);
        $voteRecord->save();
        return ['res'=>true, 'info'=>''];//不需要更新记录
    }
    public static function updateResourceResult($resource, $resourceId, $voteType){
        $votes = Vote::where('resource', $resource)
            ->where('resource_id', $resourceId)
            ->where('type', $voteType)
            ->get();
        foreach($votes as $vote){
            self::updateVoteResult($vote);
        }
    }
    public static function updateVoteResult($vote){
        $records = VoteRecord::where('vote_id', $vote->id)->get();
        $alternatives = explode(';', $vote->alternatives);
        $resArr = [];
        foreach($alternatives as $key){
            $resArr[$key] = 0;
        }
        foreach($records as $record){
            $recResArr = explode(';', $record->result);
            foreach($recResArr as $resRes){
                if(array_key_exists($resRes, $resArr)){
                    $resArr[$resRes] += 1;
                }
            }
        }
        $vote->result = '';
        if(count($alternatives)>1){
            foreach($resArr as $res=>$sum){
                $vote->result .= ($res.':'.$sum.';');
            }
        }else{
            foreach($resArr as $res=>$sum){
                $vote->result .= $sum;
            }
        }
        $vote->save();
    }
    /*计算单选选票，选票结果写入result字段中*/
    public static function caculateSingleVote($resource, $resourceId){
        $query = DB::table('t_vote_record')
                ->select('t_vote.id', 't_vote.target', DB::raw('count(t_vote_record.id) as num'))
                ->join('t_vote','t_vote.id', '=', 't_vote_record.vote_id')
                ->where('t_vote.resource', $resource)
                ->where('t_vote.resource_id', $resourceId)
                ->where('t_vote.type', Vote::SINGLE)
                ->where('t_vote_record.result', '!=', '')
                ->groupBy('t_vote.id')
                ->groupBy('t_vote.target');
        $voteResult = $query->get();
        foreach($voteResult as $v){
            $vote = Vote::where('id', $v->id)->update(['result'=>$v->num]);
        }
    }
    /*计算单选选票，选票结果写入result字段中*/
    public static function caculateMultipleVote($resource, $resourceId){
        $query = DB::table('t_vote_record')
                ->select('t_vote_record.id', 't_vote_record.vote_id', 't_vote.alternatives', DB::raw('t_vote_record.result as result'))
                ->join('t_vote','t_vote.id', '=', 't_vote_record.vote_id')
                ->where('t_vote.resource', $resource)
                ->where('t_vote.resource_id', $resourceId)
                ->where('t_vote.type', Vote::MULTIPLE);
        $voteResult = $query->get();
        $caculateArr = [];
        foreach($voteResult as $vr){
            if(!array_key_exists($vr->vote_id, $caculateArr)){
                $caculateArr[$vr->vote_id] = [];
                $alternatives = explode(';', $vr->alternatives);
                foreach($alternatives as $an){
                    if(!empty($an)){
                         $caculateArr[$vr->vote_id][$an] = 0;
                    }
                }
            }
            $results = explode(';', $vr->result);
            foreach($results as $res){
                if(!empty($res) && array_key_exists($res, $caculateArr[$vr->vote_id])){
                    $caculateArr[$vr->vote_id][$res]++;
                }
            }
        }
        foreach($caculateArr as $voteId=>$voteCaculate){
            $result = '';
            arsort($voteCaculate);//按照票数排序
            foreach($voteCaculate as $key=>$value){
                $result .= ($key.':'.$value.';');
            }
            Vote::where('id', $voteId)->update(['result'=>$result]);
        }
    }
    public static function finishVote($resource, $resourceId, $voteType){
        $votes = Vote::where('resource', $resource)->where('resource_id', $resourceId)->where('type', $voteType)->get();
        foreach($votes as $vote){
          $vote->voted = $vote->target;
          $vote->save();
        }
    }
    public static function checkVoteStatus($resource, $resourceId, $voteType){
        $votes = Vote::where('resource', $resource)->where('resource_id', $resourceId)->where('type', $voteType)->get();
        foreach($votes as $vote) {
            if($vote->voted < $vote->target){
                return false;
            }
        }
        return true;
    }
    public static function getVoteResult($resource, $resourceId, $type){
        $votes = Vote::where('resource', $resource)
            ->where('resource_id', $resourceId)
            ->where('type', $type)->orderBy('result', 'desc')->get();
        $res = [];
        for($i=0; $i<count($votes); $i++){
            $res[$votes[$i]->alternatives] = $votes[$i]->result;
        }
        return $res;
        /*
        if($type == Vote::MULTIPLE){
            $vote = Vote::where('resource', $resource)
                ->where('resource_id', $resourceId)->where('type', $type)->first();
            if(is_null($vote)){
              return [];
            }
            return explode(';', $vote->result);
        }else{
            $votes = Vote::where('resource', $resource)
                ->where('resource_id', $resourceId)
                ->where('type', $type)->orderBy('result', 'desc')->get();
            $res = [];
            foreach($votes as $vote){
                $res[$vote->alternatives] = is_null($vote->result)?0:$vote->result;
            }
            return $res;
        }
         */
    }
    /*生成任务选票*/
    public static function generateTaskVote($task, $type, $alternatives, $target){
        try{
            $vote = new Vote;
            $vote->resource = 'task';
            $vote->resource_id = $task->id;
            $vote->type = $type;
            $vote->voted = 0;
            $vote->target = $target;
            $vote->batch = date('Ymd').sprintf('%06d', $task->id);
            if(is_array($alternatives)){
                $vote->alternatives = implode(';', $alternatives);
            }else{
                $vote->alternatives = $alternatives;
            }
            $vote->save();
            return true;
        }catch(Exception $ex){
            return false;
        }
    }
}
