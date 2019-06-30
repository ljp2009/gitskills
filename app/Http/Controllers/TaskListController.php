<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskPartner;
use App\Models\User;
use Auth, DB, Redirect;
class TaskListController extends Controller
{
    /*用户任务列表*/
    public function showUserTask($listName='publish', $page=0, $userid=0){
        $uid = $userid == 0?Auth::id():$userid;
        return view('task.usertasklist',['listName'=>$listName, 'page'=>$page, 'uid'=>$uid]);
    }
    public function showUserTaskData($listName, $from, $to, $userid){
        $uid = $userid == 0?Auth::id():$userid;
        $func =camel_case('get_user_'.$listName.'_task_data');
        $tasks = $this->$func($from, $to, $uid);
        return view('task.partview.tasklistitem', array('taskList' => $tasks));
    }
    private function getUserJoinTaskData($from, $to, $uid){
        $query = TaskPartner::where('user_id',$uid)
            ->orderBy('created_at', 'desc');
        $partnerList = $query->skip($from)->take($to-$from+1)->get();
        $taskList = [];
        foreach($partnerList as $p){
            if(!is_null($p->task)){
                array_push($taskList, $p->task);
            }
        }
        return $taskList;
    }
    private function getUserPublishTaskData($from, $to, $uid){
        $query = Task::where('user_id', $uid)
            ->where('step','>','0')
            ->orderBy('created_at', 'desc');
        $taskList = $query->skip($from)->take($to-$from+1)->get();
        return $taskList;
    }
    /*资源大厅列表*/
    public function showHallTask($page = 0, $order='publish_date', $filter='0-0-0'){
        return view('task.taskhall',[
            'page' => $page,
            'order' => $order,
            'filter' => $filter]);
    }
    public function showHallTaskData($from, $to, $order='publish_date', $filter='0-0-0'){
        $query = Task::where('step', '>', '1');
        $query = self::makeFilter($query, $filter);
        $query = self::makeOrder($query, $order);
        $taskList = $query->skip($from)->take($to-$from+1)->get();
        return view('task.partview.tasklistitem', array('taskList' => $taskList));
    }
    private static function makeOrder($query, $order){
        return $query->orderBy($order, 'desc');
    }
    private static function makeFilter($query, $filter){
        if($filter != '0-0-0'){
            $fArr = explode('-', $filter);
            $query = self::makeAmountFilter($query, $fArr[0]);
            $query = self::makeModelFilter($query, $fArr[1]);
            $query = self::makeSkillFilter($query, $fArr[2]);
        }
        return $query;
    }
    private static function makeAmountFilter($query, $code){
        switch($code){
        case 1:
            return $query->where('amount','<=',500);
        case 2:
            return $query->where('amount','<=',1000)->where('amount','>=',500);
        case 3:
            return $query->where('amount','<=',2000)->where('amount','>=',1000);
        case 4:
            return $query->where('amount','>=',2000);
        default:
            return $query;
        }
    }
    private static function makeModelFilter($query, $model){
        switch($model){
        case 1:
            return $query->where('task_type', Task::TASKTYPE_PK);
        case 2:
            return $query->where('task_type', Task::TASKTYPE_APPOINT);
        default:
            return $query;
        }
    }
    private static function makeSkillFilter($query, $skill){
        if($skill == 0){
            return $query;
        }
        return $query->where('skill_type', $skill);
    }
}
