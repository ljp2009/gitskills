<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TaskMilestone;
use App\Models\Task;
use App\Models\TaskParter;
use App\Common\TaskAction;
use App\Common\TaskStep;
use Input, Auth;
class TaskMilestoneController extends Controller
{
    /*里程碑显示/操作函数*/
    public function getListAll($taskId){
        $mslist =$this->getMilestoneData($taskId);
        return view('task.partview.milestone',['items'=>$mslist, 'taskId'=>$taskId]);
    }
    public function postSignIn(){
        $taskId = Input::get('taskId');
        $id = Input::get('id');
        $ms =$this->getMilestoneData($taskId, $id);
        if($ms->allowSignIn){
            if($ms->allowSignIn == 'A'){
                $ms->status = $ms->status == 'wait'?'A':'finish';
                $ms->a_sign_date = date('Y-m-d');
            }
            if($ms->allowSignIn == 'B'){
                $ms->status = $ms->status == 'wait'?'B':'finish';
                $ms->b_sign_date = date('Y-m-d');
            }
            $ms->save();
            return response()->json(['res'=>true]);
        }
        else{
            return response()->json(['res'=>false]);
        }
    }
    public function getShow($id){
        $ms = TaskMilestone::findOrFail($id);
        return view('task.milestone',['milestone'=>$ms]);
    }

    /*里程碑管理函数*/
    public function getManage($taskId){
        return view('task.editmilestone',['taskId'=>$taskId]);
    }
    public function getAllData($taskId){
        $mslist = TaskMilestone::where('task_id', $taskId)->orderBy('date')->get();
        $msArr = [];
        foreach($mslist as $ms){
            array_push($msArr, $this->milestoneToArray($ms));
        }
        return response()->json(['res'=>true, 'data'=>$msArr]);
    }

    public function getEdit($taskid, $id){
        $ms = TaskMilestone::findOrFail($id);
        return view('task.partview.edptmilestone',['milestone'=>$ms]);
    }

    public function postSave(){
        $id = Input::get('id');
        if($id == 0){
        $ms = new TaskMilestone;
        $ms->task_id = Input::get('taskid');
        $ms->status = 'wait';
        }else{
            $ms = TaskMilestone::findOrFail($id);
        }
        $ms->text = Input::get('text');
        $ms->date = Input::get('date');
        $ms->save();
        $arr = $this->milestoneToArray($ms);
        $res = ['res'=>true, 'info'=> $arr];
        return response()->json($res);
    }
    
    public function postDelete(){
        $id = Input::get('id');
        $tm = TaskMilestone::findOrFail($id);
        $tm->delete();
        $res = ['res'=>true, 'info'=>$id];
        return response()->json($res);
    }

    public function postUpdate(){
        $ms = TaskMilestone::findOrFail($id);
        $ms->text = Input::get('text');
        $ms->status = 'wait';
        $ms->date = Input::get('date');
        $ms->save();
        $arr = $this->milestoneToArray($ms);
        $res = ['res'=>true, 'info'=> $arr];
        return response()->json($res);
    }
    /*私有函数*/
    private function milestoneToArray($ms){
        return [
            'id'=>$ms->id,
            'text'=>$ms->text,
            'isDelay'=>$ms->isDelay,
            'date'=>$ms->date
        ];
    }
    //获取全部的里程碑包含状态的数据
    private function getMilestoneData($taskId, $msId = 0){
        $task = Task::findOrFail($taskId);
        $actions = TaskAction::getActions($task);
        if(!TaskAction::checkAction($actions, TaskAction::VIEW)){
            return [];
        }

        $msList = TaskMilestone::where('task_id', $taskId)->orderBy('date')->get();
        $msRes = null;
        $signA = TaskAction::checkAction($actions, TaskAction::SIGNINA);
        $signB = TaskAction::checkAction($actions, TaskAction::SIGNINB);
        for($i=0; $i<count($msList); $i++){
            $ms = $msList[$i];
            if($msId >0 && $ms->id == $msId){
                $msRes = $ms;
            }
            if($task->step != TaskStep::DELIVERY){
                continue;
            }
            if($ms->status == 'finish'){
                continue;
            }
            $ms->isActive = true;
            if($signA && $ms->status != 'A'){
                $ms->allowSignIn = 'A';
            }
            if($signB && $ms->status != 'B'){
                $ms->allowSignIn = 'B';
            }
            //未到期的最近一个里程碑以及之前的全部为完成的里程碑都处于活动状态
            if(!$ms->isDelay){
                break;
            }
        }
        if($msId >0){
            return $msRes;
        }else{
            return $msList;
        }
    }
    private function getUserRole($taskId){
        if(!Auth::check()) return 'visitor';
        $task = Task::findOrFail($taskId);
        $id = Auth::id();
        if($task->user_id == $id) {
            return 'owner';
        }
        if($task->appointPartner && $task->appointPartner->user_id == $id){
            return 'partner';
        }
        return 'visitor';
    }
}
