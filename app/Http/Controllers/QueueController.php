<?php
/**
 * 队列控制器
 * @author ViewsTap xiaocui Programmer
 * @date 20160217
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Commands\EvaluationDelivery;
use App\Common\CommonUtils;
use App\Models\Task;
use App\Models\TaskDelivery;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteResult;
use App\Http\Controllers\Common\CommonHandelGoldController;
use App\Common\GoldManager as GM;
use Auth, Redirect, Input,DB,Queue;
class QueueController extends Controller
{
	/**
	/* 说明
	 * 定时执行 任务调度 在  \app\Console 里的kernel.php添加定时任务
	 *	$schedule->call('QueueController@index')->dailyAt('16:05');
	 *
	 *	php artisan schedule:run //在composer运行此句开启任务调度
	 *  只要在服务器上设置一个每分钟调用 artisan schedule:run 的 cron job, 一切就准备就绪了
	 * @see \Illuminate\Routing\Controller::__call()
	 */
	public static $SINGLE_VOTE_DAYS = 2;
	public static $MULITPLE_VOTE_DAYS = 2;
	public static $RESOURCE_PKTASK = 'PKTASK';

	private static $ASSIGN_SOLUTION = ['1' => '1:0.5;2:0.4;+:0.1', '2' => '1:0.8;+:0.2'];

	public function index(){
        $this->mainIndex(date('Y-m-d'));
	}

    public function mainIndex($date){
		$tasks = $this->getPkTask($date);
		$users = $this->getPushUsers();
		foreach($tasks as $key=>$task){
			$days = CommonUtils::calculateDays(strtotime($date),strtotime($task->delivery_date));
			$firstCount = Vote::where('task_id',$task->id)->where('step',1)->count();
			if($days == 0){//第一轮投票
				$delivery = TaskDelivery::where('task_id',$task->id)->get();
				foreach ($users as $k=>$v){
					$num = $k%count($delivery);
					CommonUtils::createPrivateLetter($v,0,'singlevote',array('link'=>'/task/singlevote/'.$delivery[$num]->id));
				}
			}else if($task->delivery_date < $date && $days == self::$SINGLE_VOTE_DAYS){//第二轮投票
				foreach ($users as $k=>$v){
					CommonUtils::createPrivateLetter($v,0,'multiplevote',array('link'=>'/task/multiplevote/'.$task->id));
				}
			}else if($days == self::$SINGLE_VOTE_DAYS+self::$MULITPLE_VOTE_DAYS){//投票结束 更新状态
				$task->step = 5;
				$task->save();
				$voteTask = Vote::where('task_id',$task)->get();
				foreach ($voteTask as $k=>$vote){
					$vote->status = 2;
					$vote->save();
				}
			}
		}
        return 'true';
    }
	/**
	 * 获取推送的用户
	 * @return unknown
	 */
	public function getPushUsers(){
		$users = DB::table('t_user')->lists('id');
		return $users;
	}
	public function show(){
		$this->assignSolution();
		die;
		return view('test',array('task'=>$task));
	}

	/**
	 * 获取pk模式待交付的任务列表
	 * @return unknown
	 */
	public function getPkTask($date){
		$page = 1;
		$tasks = [];
		do{
			$task1 = Task::where('delivery_date',$date)->where('task_type',Task::TASKTYPE_PK)
						->whereIn('step',array(Task::TASKSTEP_WAIT_COMFIRM,Task::TASKSTEP_WAIT_JOIN))->skip(($page-1)*100)->take(100)->get()->toArray();
			if(!empty($task1)){
				$tasks = array_merge($tasks,$task1);
			}
			$page ++;
		}while( !empty($task1) && is_array($task1) );
// 		print_r($tasks);die;
		if(!empty($tasks)){
			foreach($tasks as $k=>$task2){
				$myTask = Task::find($task2['id']);
				if($task2['step'] == Task::TASKSTEP_WAIT_JOIN){
					$myTask->step = Task::TASKSTEP_CANCEL;
					$myTask->save();
				}else if($task2['step'] == Task::TASKSTEP_WAIT_COMFIRM){
					$myTask->step = Task::TASKSTEP_WAIT_DELIVERY;
					$myTask->save();
				}
			}
		}
		$task = Task::where('task_type',Task::TASKTYPE_PK)->where('step',Task::TASKSTEP_WAIT_DELIVERY)->get();
// 		print_r($task);die;
		return $task;
	}
	
	/**
	 * 统计结果
	 */
	public function countVoteResult(){
        $this->mainCountVoteResult(date('Y-m-d'));
	}
    public function mainCountVoteResult($date){
		$page = 1;
		$votes = [];
		do{
			$vote = Vote::where('status',1)->skip(($page-1)*100)->take(100)->get()->toArray();
			if(!empty($vote)){
				$votes = array_merge($votes,$vote);
			}
			$page ++;
		}while( !empty($vote) && is_array($vote) );
		$delivery = [];
		foreach ($votes as $k=>$v){
			if(!isset($delivery[$v['delivery_id']])){
				$delivery[$v['delivery_id']]['like_count'] = Vote::where('delivery_id',$v['delivery_id'])->where('is_like',1)->count();
				$delivery[$v['delivery_id']]['dislike_count'] = Vote::where('delivery_id',$v['delivery_id'])->where('is_like',0)->count();
				$delivery[$v['delivery_id']]['all'] = Vote::where('delivery_id',$v['delivery_id'])->count();
				$delivery[$v['delivery_id']]['task_id'] = $v['task_id'];
			}
		}
		foreach ($delivery as $k=>$v){
			$voteResult = VoteResult::where('delivery_id',$k)->first();
			if(empty($voteResult)){
				$voteResult = new VoteResult;
				$voteResult->task_id = $v['task_id'];
				$voteResult->delivery_id = $k;
				$voteResult->like_count = $v['like_count'];
				$voteResult->dislike_count = $v['dislike_count'];
				$voteResult->ratio = $v['like_count']/$v['all'];
				$voteResult->save();
			}else{
				$voteResult->like_count = $v['like_count'];
				$voteResult->dislike_count = $v['dislike_count'];
				$voteResult->ratio = $v['like_count']/$v['all'];
				$voteResult->save();
			}
		}
        return 'true';
    }
	/**
	 * 最终分配
	 * 
	 */
	public function assignSolution(){
		$this->mainAssignSolution(date('Y-m-d'));
	}
    public function mainAssignSolution($date){
		$tasks = [];
		$page = 1;
		do{
			$task = Task::where('task_type',Task::TASKTYPE_PK)->where('step',Task::TASKSTEP_WAIT_PAY)->skip(($page-1)*100)->take(100)->get()->toArray();
			if(!empty($task)){
				$tasks = array_merge($tasks,$task);
			}
			$page ++;
		}while( !empty($task) && is_array($task) );
		foreach ($tasks as $k=>$val){
			$payMoney = [];
			$gold = 0;
// 			$deliveryList = VoteResult::where('task_id',$val->id)->orderBy('ratio', "desc")->take(3)->get();
			$deliveryList = DB::table('t_vote_result')->join('t_task_delivery', 't_vote_result.delivery_id', '=', 't_task_delivery.id')
					->orderBy('t_vote_result.ratio', 'desc')->orderBy('t_task_delivery.created_at', 'asc')
					->select('t_task_delivery.id', 't_task_delivery.user_id','t_task_delivery.task_id')->get();
// 			print_r($deliveryList);die;
			$userNum = count($deliveryList);
			$taskInfo = Task::find($val['id']);
			$solution = self::$ASSIGN_SOLUTION[$taskInfo->assign_solution];
			$solutionArr = explode(';',$solution);
			$rankNum = count($solutionArr);
			if($userNum >= $rankNum){
				foreach ($deliveryList as $key=>$delivery){
					if($taskInfo->assign_solution == 1){
						if($key == 0){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}elseif($key == 1){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}else{
							$moneySolution = explode(':',$solutionArr[2]);
							$remailNum = $userNum - 2;
							$money = $val['amount']*$moneySolution[1]/$remailNum;
							if($money < 1){
								$assignNum = floor($val['amount']*$moneySolution[1]);
								if($key <= (1+$assignNum)){
									$gold = 1;
									$payMoney[$key] = $gold;
								}else{
									$gold = 0;
									break;
								}
							}else{
								$gold = floor($val['amount']*$moneySolution[1]/$remailNum);
								$payMoney[$key] = $gold;
							}
						}
					}else if($taskInfo->assign_solution == 2){
						if($key == 0){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}else{
							$moneySolution = explode(':',$solutionArr[1]);
							$remailNum = $userNum - 1;
							$money = $val['amount']*$moneySolution[1]/$remailNum;
							if($money < 1){
								$assignNum = floor($val['amount']*$moneySolution[1]);
								if($key <= ($assignNum)){
									$gold = 1;
									$payMoney[$key] = $gold;
								}else{
									$gold = 0;
									break;
								}
							}else{
								$gold = floor($val['amount']*$moneySolution[1]/$remailNum);
								$payMoney[$key] = $gold;
							}
						}
					}
					if($gold>0){
						GM::incomeGold($gold,'5000109',$val['id'],$delivery->user_id,'完成任务获得金币奖励');
					}
				}
				if(!empty($deliveryList)){
					$taskInfo->step = Task::TASKSTEP_FINISH;
					$taskInfo->save();
				}
			}else if($userNum < $rankNum){
				foreach ($deliveryList as $key=>$delivery){
					if($taskInfo->assign_solution == 1){
						if($key == 0){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}elseif($key == 1){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}
					}else if($taskInfo->assign_solution == 2){
						if($key == 0){
							$moneySolution = explode(':',$solutionArr[$key]);
							$gold = floor($val['amount']*$moneySolution[1]);
							$payMoney[$key] = $gold;
						}
					}
					if($gold>0){
						GM::incomeGold($gold,'5000109',$val['id'],$delivery->user_id,'完成任务获得金币奖励');
					}
				}
				if(!empty($deliveryList)){
					$taskInfo->step = Task::TASKSTEP_FINISH;
					$taskInfo->save();
				}
			}
		}
        return 'true';
    }
}
?>
