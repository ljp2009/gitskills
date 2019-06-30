<?php
/**
 * Pk任务结束任务
 * @author ViewsTap xiaocui Programmer
 * @date 20160217
 */
namespace App\Schedules;

use App\Models\Task;
use App\Models\TaskDelivery;
use App\Common\TaskStep;
use App\Common\TaskModel;
use App\Common\CommonUtils;
use App\Common\VoteHandler;
use App\Common\GoldManager;
use DB;

class PkTaskSchedule
{
    const SINGLE_VOTE_DAYS = 2;//第一轮投票最大天数
    const MULTIPLE_VOTE_DAYS = 2;//第二轮投票最大天数
    const SINGLE_VOTE_USERS = 5;//第一轮投票，单票目标次数
    const MULTIPLE_VOTE_USERS = 100;//第二论投票，单票目标次数

    const R1_VOTE_DAYS = 2; //第一轮投票最大天数
    const R2_VOTE_DAYS = 2;//第二轮投票最大天数
    const R1_VOTE_TARGET = 5;//第一轮投票，单票目标次数
    const R2_VOTE_TARGET = 100;//第二论投票，单票目标次数
    const R1_MIN_VOTE = 3;//第一轮排名，Vote阀值
    const R2_IMG_ALTERNATIVES = 6; //第二论，图片备选目标数
    const R2_TXT_ALTERNATIVES = 3; //第二轮，文字备选目标数
    const ASSIGN_TYPE = [
        1=>[ 1=>80, 2=>15, 3=>5  ], //分配方案1
        2=>[ 1=>50, 2=>30, 0=>20 ]  //分配方案2
    ];
    const SYSTEMUSER = 0;
    private $idList = null;
    private $nowDate = null;
    private $usedUserIds=[];
    private $usedFullIds=[];
    public $debug = [];
    /**
     * 执行“任务”的批量计划
     * */
    public function run($step=[1,2,3,4]){
        if(is_null($this->nowDate)){
            $this->nowDate = date('Y-m-d');
        }
        $this->writeDebug('Date : '.$this->nowDate);
        //获取评并处理评选阶段的任务
        $query = Task::whereIn('step', [TaskStep::REVIEW_1, TaskStep::REVIEW_2])
            ->where('task_type', TaskModel::PK);//评审阶段的任务
        if(!is_null($this->idList)){
            $query = $query->whereIn('id', $this->idList);
        }
        $tasks = $query->get();
        $this->writeDebug('Review Task Count : '.count($tasks));
        foreach($tasks as $task){
            $this->operationTask($task);
        }
        //获取交付阶段到期的任务
        $query =Task::whereIn('step', [TaskStep::PUBLISHED, TaskStep::CHOICING, TaskStep::DELIVERY])//PK任务会一直处于备选阶段
            ->where('delivery_date', '<=', $this->nowDate)
            ->where('task_type', TaskModel::PK);//评审阶段的任务
        if(!is_null($this->idList)){
            $query = $query->whereIn('id', $this->idList);
        }
        $tasks = $query->get();
        $this->writeDebug('Delivery Task Count : '.count($tasks));
        foreach($tasks as $task){
            $this->finishTaskDelivery($task);
        }
        return $this->debug;
    }
    private function writeDebug($obj, $level=0){
        array_push($this->debug,$obj);
    }

    // 设置debug，用于调试任务处理的范围
    public function setIdList($idList=null){
        $this->idList = $idList;
    }
    // 设置debug，用于调试任务处理的时间
    public function setDate($date){
        $this->nowDate =$date;
    }
    //处理单个任务
    private function operationTask($task){
        $res = $this->updateVotedTask($task);
        if(!$res){
            $res = $this->updateTimeoutTask($task);
        }
    }
    //更新投票已经完成的任务
    private function updateVotedTask($task){
        $taskStep = $task->step;
        //查询投票是否全部完成
        $checkRes = VoteHandler::checkVoteStatus('task', $task->id, $task->getVoteCode());
        $this->writeDebug('Task['.$task->id.'] Vote Status: '.$checkRes);
        if($checkRes){
            if($taskStep == TaskStep::REVIEW_1){
                $this->updateTaskStepToR2($task);
            }else{
                $this->finishTaskStep($task);
            }
        }
        return $checkRes;
    }
    //更新已经到达了投票期限的任务
    private function updateTimeoutTask($task){
        $taskUpdateDate = date('Y-m-d', strtotime($task->updated_at));
        $nowDate =$this->nowDate;
        $taskStep = $task->step;
        $this->writeDebug('Task['.$task->id.'] step: '. $taskStep);
        $r1BeginDate = date('Y-m-d', strtotime('-'.self::R1_VOTE_DAYS.' days', strtotime($nowDate)));
        $r2BeginDate = date('Y-m-d', strtotime('-'.self::R2_VOTE_DAYS.' days', strtotime($nowDate)));
        //检查超时
        if($taskStep == TaskStep::REVIEW_1 && $taskUpdateDate < $r1BeginDate){
            $this->writeDebug('Task['.$task->id.'] R1 Timeout Status: true');
            VoteHandler::finishVote('task', $task->id, $task->getVoteCode());
            $this->updateTaskStepToR2($task);
        }
        else if($taskStep == TaskStep::REVIEW_2 && $taskUpdateDate < $r2BeginDate) {
            $this->writeDebug('Task['.$task->id.'] R2 Timeout Status: true');
            VoteHandler::finishVote('task', $task->id, $task->getVoteCode());
            $this->finishTaskStep($task);
        }
    }
    //更新任务到第二论评审步骤
    private function updateTaskStepToR2($task){
        $this->writeDebug('Task['.$task->id.'] Update To R2');
        $voteType = $task->getVoteType();
        $voteCode = $task->getVoteCode();
        $this->writeDebug('Task['.$task->id.'] skillType :'. $task->skill_type);
        $this->writeDebug('Task['.$task->id.'] VoteType :'. $voteType);
        $this->writeDebug('Task['.$task->id.'] VoteCode :'. $voteCode);
        VoteHandler::updateResourceResult('task', $task->id, $voteCode);
        $voteRes = VoteHandler::getVoteResult('task', $task->id, $voteCode);
        $this->writeDebug($voteRes);
        $deliverys   = TaskDelivery::where('task_id', $task->id)->orderBy('like_sum', 'desc')->get();
        $finalOrders = [];
        $voteType = $task->getVoteType();
        $orderCt = ($voteType == 'image'?self::R2_IMG_ALTERNATIVES:self::R2_TXT_ALTERNATIVES);

        //添加到达阀值的交付作为备选（按照like排名）
        foreach($deliverys as $delivery){
            $vres = array_key_exists($delivery->id, $voteRes)?$voteRes[$delivery->id]:0;
            if($vres >= self::R1_MIN_VOTE){
                array_push($finalOrders, $delivery->id);
                if(count($finalOrders) == $orderCt){
                    break;
                }
            }
        }
        //总量不足的时候， 添加未到达阀值的交付作为备选（按照like排名）
        if(count($finalOrders) < $orderCt){
            foreach($deliverys as $delivery){
                if(!in_array($delivery->id, $finalOrders)){
                    array_push($finalOrders, $delivery->id);
                    if(count($finalOrders) == $orderCt){
                        break;
                    }
                }
            }
        }
        $this->writeDebug('Task['.$task->id.'] Update To R2 alternatives:'. implode(',', $finalOrders));
        //生成多选的选票
        VoteHandler::generateTaskVote($task, $voteCode+1, $finalOrders, self::R2_VOTE_TARGET);

        //更新任务到第二评审阶段
        $task->step = TaskStep::REVIEW_2;
        $task->save();
    }
    //结束任务
    private function finishTaskStep($task){
        $this->writeDebug('Task['.$task->id.'] Finish Task');
        $voteType = $task->getVoteType();
        $this->writeDebug('Task['.$task->id.'] VoteType :'. $voteType);
        $voteCode = $task->getVoteCode();
        $this->writeDebug('Task['.$task->id.'] VoteCode :'. $voteCode);
        VoteHandler::updateResourceResult('task', $task->id, $voteCode);
        $voteRes  = VoteHandler::getVoteResult('task', $task->id, $voteCode);
        $this->writeDebug($voteRes);
        $resArr = [];
        //计算排名, 按照like倒叙，创建次序正序进行排名。
        foreach($voteRes as $res){
            $tmpArr = explode(';', $res);
            foreach($tmpArr as $tmp){
                $tmpObj = explode(':', $tmp); 
                if(count($tmpObj) != 2) continue;
                $orderCode = sprintf('%04d', $tmpObj[1]).(100000000-$tmpObj[0]);
                $resArr[$tmpObj[0]] = $orderCode;
            }
        }
        arsort($resArr);
        $orderResult = [];
        $i = 1;
        foreach($resArr as $id => $code){
            $orderResult[$i] = $id;
            $i++;
        }
        $this->writeDebug('Task['.$task->id.'] assign amount:'. implode(',', array_values($orderResult)));
        //分配任务奖金
        $this->assignTaskAmount($task, $orderResult);
        //更新任务到完成阶段
        $task->step = TaskStep::FINISH;
        $task->save();
    }
    //根据预设的任务奖金分配方案分配任务的金额
    private function assignTaskAmount($task, $orderResult){
        $this->writeDebug('Task['.$task->id.'] assign amount:'. implode(',', array_values($orderResult)));
        $assignSolution = self::ASSIGN_TYPE[$task->assign_solution];
        $maxOrder = max(array_keys($assignSolution));//最大排名数（用来判断是否有足够的交付进行分配）
        $resCt = count($orderResult);
        $deliverys = [];
        $amount = $task->amount;
        $assigned = 0;
        $guarantee = GoldManager::findGuarantee('task', $task->id);
        $ids = [];
        // 分配获奖部分
        foreach($assignSolution as $key=>$percent){
            if($key == 0) continue; //忽略平均分配的比例
            if(array_key_exists($key, $orderResult)){ //仅针对结果中有的名次分配，其他不分配
                $deliveryId = $orderResult[$key];
                $delivery = TaskDelivery::find($deliveryId);
                $userAmount = (int)($amount*$percent/100);
                $log = 'PK任务['.$task->id.']奖金,user['.$delivery->user_id.'],第['.$key.']名次,奖金('.$userAmount.') ';
                $assigned += $userAmount;
                $res = GoldManager::guaranteeDealPay($guarantee, $userAmount, $delivery->user_id, $log);
                array_push($ids, $delivery->id);
                $this->writeDebug($res);
                $this->writeDebug($log);
                $msg = '您在任务['.$task->title.']的评审中获得了第['.$key.']名，得到奖金：'.$userAmount.'。';
                CommonUtils::createPrivateLetter($delivery->user_id, 0, $msg, '' , "personal");
            }
        }
        // 分配平均分配部分, 当提交的结果小于最大的分配值的时候，不会存在更多的交付了，所以不需要继续分配平均部分
        if($maxOrder <= $resCt && array_key_exists(0, $assignSolution)){
            $otherDeliverys = TaskDelivery::where('task_id', $task->id)->whereNotIn('id', $ids)->orderBy('id')->get();
            $dct = count($otherDeliverys); 
            if($dct > 0){
                $allUserAmount = (int)($amount*$assignSolution[0]/100);
                if($allUserAmount < $dct){
                   $userAmount = 1; 
                   $this->writeDebug('PK任务['.$task->id.']奖金,平分奖金不充足, 总金额('.$allUserAmount.'), 平分交付数('.$dct.')');
                }else{
                   $userAmount = (int)($allUserAmount/$dct); 
                   $this->writeDebug('PK任务['.$task->id.']奖金,平分奖金充足，总金额('.$allUserAmount.'), 平分交付数('.$dct.') 平均金额('.$userAmount.')');
                }
                $i = 0;
                foreach($otherDeliverys as $delivery){
                    $assigned += $userAmount;
                    $log = 'PK任务['.$task->id.']奖金,user['.$delivery->user_id.'], 金额('.$userAmount.')';
                    $res = GoldManager::guaranteeDealPay($guarantee, $userAmount, $delivery->user_id, $log);
                    $this->writeDebug($res);
                    $this->writeDebug($log);
                    $msg = '您在任务['.$task->title.']的评审中获得参与奖，奖金：'.$userAmount.'。';
                    CommonUtils::createPrivateLetter($delivery->user_id, 0, $msg, '' , "personal");
                    $i++;
                    if($i >= $allUserAmount){
                        break;
                    }
                }
            }
        }
        $remainAmount = $amount - $assigned; //尚未分配部分（包含参与者不足或者分配计算时候抹去的小数部分的和）  
        if($remainAmount > 0){
            $log ='PK任务['.$task->id.'],退回参与不足分配的部分和零钱('.$remainAmount.')'; 
            $res = GoldManager::guaranteeDealPay($guarantee, $remainAmount, $task->user_id, $log);
            $this->writeDebug($res);
            $this->writeDebug($log);
        }
        $msg = '您的任务['.$task->title.']评审已经完成。';
        CommonUtils::createPrivateLetter($task->user_id, 0, $msg, '' , "personal");
        if($resCt > 0){
            // 有人参与，扣除服务费
            if($guarantee->remain_gold > 0){
                $log ='PK任务['.$task->id.'],系统扣除服务费用('.$guarantee->remain_gold.')'; 
                $res = GoldManager::guaranteeDealPay($guarantee,
                   $guarantee->remain_gold, self::SYSTEMUSER, $log);
                $this->writeDebug($res);
                $this->writeDebug($log);
            }
        }else{
            // 无人参与，退回服务费
            if($guarantee->remain_gold > 0){
                $log ='PK任务['.$task->id.'],无人参与，退回服务费('.$guarantee->remain_gold.')'; 
                $res = GoldManager::guaranteeDealPay($guarantee,
                   $guarantee->remain_gold, $task->user_id, $log);
                $this->writeDebug($res);
                $this->writeDebug($log);
            }
        }
    }
    //停止已经到评审日期的任务
    private function finishTaskDelivery($task){
        $this->writeDebug('Task['.$task->id.'] finish Delivery');
        //获取任务交付
        $deliverys = TaskDelivery::where('task_id', $task->id)->get();
        $voteType = $task->getVoteType();
        $voteCode = $voteType=='image'? 1: 11;
        $deliCt = count($deliverys);
        $orderCt = ($voteCode == 'image'?self::R2_IMG_ALTERNATIVES:self::R2_TXT_ALTERNATIVES);
        //交付数量未到第二轮评审的任务数
        if($deliCt <= 1){//仅有一个或者无交付, 直接分配奖励,任务结束
            $orderResult = [];
            foreach($deliverys as $delivery){
                $orderResult = [1=>$delivery->id];
            }
            $this->assignTaskAmount($task, $orderResult);
            $task->step = TaskStep::FINISH;
            $task->save();
        }
        else if($deliCt > 1 && $deliCt <= $orderCt) {//有交付但是小于等于第二轮评选的数量,任务进入第二轮评选
            $ids = [];
            foreach($deliverys as $delivery){
                array_push($ids, $delivery->id);
            }
            VoteHandler::generateTaskVote($task, $voteCode+1, $ids, self::R2_VOTE_TARGET);
            $task->step = TaskStep::REVIEW_2;
            $task->save();
        }
        else if($deliCt > $orderCt) {//有交付，并且大于第二轮评选数量,任务进入第一轮评选
            foreach($deliverys as $delivery){
                VoteHandler::generateTaskVote($task, $voteCode, $delivery->id, self::R1_VOTE_TARGET);
            }
            $task->step = TaskStep::REVIEW_1;
            $task->save();
        }
        //给参与者发送私信
        if($deliCt > 0){
            $msg = '您参与的任务['.$task->title.']，已经进入评审阶段。';
            foreach($deliverys as $delivery){
                CommonUtils::createPrivateLetter($delivery->user_id, 0, $msg, '' , "personal");
            }
        }
        //给发起者发私信
        $msg = '您的任务['.$task->title.']，已经进入评审阶段。';
        CommonUtils::createPrivateLetter($task->user_id, 0, $msg, '' , "personal");
    }
}
