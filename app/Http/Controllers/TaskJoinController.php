<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskPartner;
use App\Common\TaskAction;
use App\Common\TaskStep;
use App\Common\TaskModel;
use App\Common\CreditManager;
use App\Common\TaskPartnerStatus;
use Auth, DB, Input, Redirect;
class TaskJoinController extends Controller
{
    //显示请求列表 
    public function showJoinRequestListPage($page, $pid)
    {
        $task = Task::findOrFail($pid);
        $actions = TaskAction::getActions($task);
        if(TaskAction::checkAction($task, TaskAction::AGREEJOIN)){
            return view('task.requestjoinlist', array('id' => $pid, 
                'page' => $page, 'type' => 'jointask', 'listName' => 'request', 'task'=>$task));
        }
        return errorPage();
    }
    public function getJoinRequestListData($from, $to, $pid)
    {
        $task = Task::findOrFail($pid);
        if(TaskAction::checkAction($task, TaskAction::AGREEJOIN)){
            $tplist = TaskPartner::where('task_id', $pid)
                ->orderBy('status', "desc")
                ->orderBy('created_at', "asc")
                ->skip($from)->take($to - $from + 1)->get();
            return view('partview.taskparteritem', ['models' => $tplist,'pid'=>$pid]);
        }
        return errorPage();
    }

    //领取任务
    public function requestJoin()
    {
        $userId = Input::get("userid");
        $taskId = Input::get("taskid");
        $task = Task::findOrFail($taskId);
        if(TaskAction::checkAction($task, TaskAction::REQUESTJOIN)){
            $user = Auth::user();
            if(!$this->checkUserSkill($task, $user) || !$this->checkUserCredit($task, $user)){
                return self::actionResult(false,'申请失败，您不符合发布者对技能的要求。');
            }
            $taskPartner                      = new TaskPartner;
            $taskPartner->task_id             = $taskId;
            $taskPartner->user_id             = $user->id;
            $taskPartner->status              = TaskPartnerStatus::REQUEST;
            $taskPartner->request_description = $user->display_name."申请任务";
            $taskPartner->save();
            //生成成时间线
            return self::actionResult(true, '申请成功。请等待发布者的回复！～');
        }
        return self::actionResult(false,'操作失败，请稍刷新后再试。' );
    }
    //检查用户的信誉与技能是否符合要求
    private function checkUserSkill($task, $user){
        if($task->skill_level == 0){
            return true;
        }
        $userSkillStatus = $user->getSkillInfo($task->skill_type);
        if(is_null($userSkillStatus) || $userSkillStatus->level< $task->skill_level){
            return false;
        }
        return true;
    }
    private function checkUserCredit($task, $user){
        return CreditManager::checkLevel($user->id, $task->credit_level);
    }

    //同意参与
    public function agreeJoin()
    {
        $userId = Input::get("userid");
        $taskId = Input::get("taskid");
        $task = Task::find($taskId);
        if(TaskAction::checkAction($task, TaskAction::AGREEJOIN)){
            $joinRequest = TaskPartner::where('user_id', $userId)
                           ->where('task_id',$task->id)
                           ->where('status', TaskPartnerStatus::REQUEST)
                           ->first();
            if(is_null($joinRequest)){
                return self::actionResult(false, '您可能已经同意这个用户参与任务了。');
            }
            //PK任务不会经历确认阶段直接成为合作者
            if($task->model == TaskModel::PK){
                $joinRequest->status = TaskPartnerStatus::PARTNER;
            }else{
                $joinRequest->status = TaskPartnerStatus::JOININ;
            }
            $joinRequest->save();
            //如果任务处于publish阶段，同意参与者之后进入选择阶段
            if($task->step == TaskStep::PUBLISHED){
                $task->step = TaskStep::CHOICING;
                $task->save();
            }
        }
        return self::actionResult(true,'');
    }

    //拒绝参与
    public function rejectJoin()
    {
        $userId = Input::get("userid");
        $taskId = Input::get("taskid");
        $task = Task::find($taskId);
        if(TaskAction::checkAction($task, TaskAction::REJECTJOIN)){
            $joinRequest = TaskPartner::where('user_id', $userId)
                            ->where('task_id',$task->id)
                            ->where('status', TaskPartnerStatus::REQUEST)
                            ->first();
            if(is_null($joinRequest)){
                return self::actionResult(false, '您可能已经拒绝过这个用户参与任务了。');
            }
            $joinRequest->status = TaskPartnerStatus::REJECT;
            $joinRequest->save();
        }
        return self::actionResult(true,'');
    }

    //撤销操作
    public function undoAction()
    {
        $userId = Input::get("userid");
        $taskId = Input::get("taskid");
        $task = Task::find($taskId);
        if(TaskAction::checkAction($task, TaskAction::REJECTJOIN)){
            $joinRequest = TaskPartner::where('user_id', $userId)
                            ->where('task_id',$task->id)
                            ->whereIn('status',[TaskPartnerStatus::REJECT, TaskPartnerStatus::JOININ])
                            ->first();
            if(is_null($joinRequest)){
                return self::actionResult(false, '您可能已经操作过了。');
            }
            $undoAgree = $joinRequest->status == TaskPartnerStatus::JOININ;
            $joinRequest->status = TaskPartnerStatus::REQUEST;
            $joinRequest->save();
            //约定任务撤回“同意加入”的请求之后，需要检查是否撤回刚刚发布的状态
            if($undoAgree && $task->model == TaskModel::APPOINT){
                $choicCount = TaskPartner::where('task_id', $task->id)
                    ->where('status', TaskPartnerStatus::JOININ)
                    ->count();
                if($choicCount == 0){//撤回所有同意加入的请求后，撤回状态以保证任务可修改
                    $task->step = TaskStep::PUBLISHED;
                    $task->save();
                }
            }
        }
        return self::actionResult(true,'');
    }
    public function confirmJoin(){
        $taskId = Input::get("taskid");
        $task = Task::find($taskId);
        $userId = Auth::id();
        if(TaskAction::checkAction($task, TaskAction::CONFIRMJOIN)){
            $joinRequest = TaskPartner::where('user_id', $userId)
                           ->where('task_id',$task->id)
                           ->where('status', TaskPartnerStatus::JOININ)
                           ->first();
            if(is_null($joinRequest)){
                return self::actionResult(false, '你不再待确认名单中，可能发布者撤销申请或者其他备选者已经确认了。');
            }
            //修改备选者状态为合作者
            $joinRequest->status = TaskPartnerStatus::PARTNER;
            $joinRequest->save();
            //修改其他备选者状态为淘汰者
            TaskPartner::where('user_id', $userId)
                           ->where('status', TaskPartnerStatus::JOININ)
                           ->Update(['status'=>TaskPartnerStatus::REJECT]);
            //任务进入交付阶段
            $task->step = TaskStep::DELIVERY;
            $task->save();
            return self::actionResult(true,'');
        }
        return self::actionResult(false,'无操作权限。');
    }
    private function checkRole($taskId){
        if(!Auth::check()) return '';
    }
    public static function actionResult($result, $desc, $action="")
    {
        $arr = array('res' => $result, 'desc' => $desc, 'action' => $action);
        return response()->json($arr);
    }

}
