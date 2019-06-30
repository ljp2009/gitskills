<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysUserSkill;
use App\Models\Task;
use App\Models\TaskCancelRequest;
use App\Models\TaskDeliverCondition;
use App\Models\TaskScore;
use App\Models\TaskTimeline;
//use App\Common\TaskTimelineManager as TM;
use App\Common\TaskAction;
use App\Common\TaskStep;
Use App\Common\GoldManager;
use Auth, DB, Input, Redirect;

class TaskController extends Controller
{
    const TASKAUTH_VISITOR = 'visitor'; //未登录的访问者
    const TASKAUTH_CREATOR = 'creator'; //任务创建者
    const TASKAUTH_USER    = 'user'; //普通用户
    const TASKAUTH_PARTER  = 'partner'; //参与者

    const RETURNACTION_NONE    = 'none'; // 什么都不做
    const RETURNACTION_REFRESH = 'refresh'; // 刷新页面
    const RETURNACTION_TOHALL  = 'toHall'; // 返回大厅
    const RETURNACTION_TOLOGIN = 'toLogin'; // 返回大厅
    /**
     * 显示任务信息
     **/
    public function showTask($id)
    {
        $task = Task::findOrFail($id);
        $actions = TaskAction::getActions($task);
        //至少具有查看或者预览权限
        if(TaskAction::checkAction($actions, TaskAction::VIEW) 
            || TaskAction::checkAction($actions, TaskAction::PREVIEW)){
            $ctrBar =$this->getCtrlBarItems($actions);
            return self::taskview('showtask',array(
                'task'    => $task,
                'actions' => $ctrBar
            ));
        }
        return errorPage();
    }
    private function getCtrlBarItems($actions){
        $items = [];
        $showView = '';
        foreach($actions as $act){
            switch($act){
            case TaskAction::VIEW:
                $showView = 'running';
                break;
            case TaskAction::PREVIEW://当具有预览权限时（发布前）, 仅可以执行返回操作, 并跳出判断循环
                $items = ['back'];
                break 2;
            case TaskAction::CREATE:
                break;
            case TaskAction::MODIFY:
                array_push($items, 'manage');
                break;
            case TaskAction::DELETE:
                break;
            case TaskAction::PUBLISH:
                break;
            case TaskAction::REQUESTJOIN:
                array_push($items, 'requestJoin');
                break;
            case TaskAction::AGREEJOIN:
                array_push($items, 'viewJoinRequest');
                break;
            case TaskAction::UNDOAGREE:
                break;
            case TaskAction::REJECTJOIN:
                break;
            case TaskAction::UNDOREJECT:
                break;
            case TaskAction::CONFIRMJOIN:
                array_push($items, 'confirmJoin');
                break;
            case TaskAction::WAITAGREE:
                $showView = 'waitAgree';
                break;
            case TaskAction::BEREJECTED:
                $showView = 'berejected';
                break;
            case TaskAction::VIEWMILESTONE:
                break;
            case TaskAction::SIGNINA:
                break;
            case TaskAction::SIGNINB:
                break;
            case TaskAction::CANCEL:
                break;
            case TaskAction::FINISH:
                array_push($items, 'finish');
                break;
            case TaskAction::INVITE:
                array_push($items, 'invite');
                break;
            case TaskAction::DELIVERY:
                array_push($items, 'delivery');
                break;
            case TaskAction::VIEWDELIVERY:
                array_push($items, 'viewDelivery');
                break;
            case TaskAction::REQUESTCANCEL:
                array_push($items, 'cancel');
                break;
            case TaskAction::UNDOCANCELREQUEST:
                array_push($items, 'cancelStatus');
                break;
            case TaskAction::WAITCANCEL:
                array_push($items, 'cancelStatus');
                break;
            case TaskAction::CONFIRMCANCEL:
                array_push($items, 'confirmCancel');
                break;
            case TaskAction::LOGIN:
                array_push($items, 'login');
                break;
            }
        }
        if(count($items) == 0 && !empty($showView)){
            array_push($items, $showView);
        }
        return $items;
    }
    /*
    /**
     *  加载页面任务页面上的子页面
     **/
    public function showTaskPartview($id, $viewname)
    {
        $fun = 'load' . studly_case($viewname);
        return $this->$fun($id);
    }
    private function loadTaskdesc($id)
    {
        $task = Task::find($id);
        return self::partview('taskdesc', array('task' => $task));
    }
    private function loadTaskcondition($id)
    {
        $conditions = TaskDeliverCondition::where('task_id',$id)
            ->orderBy('type')->get();
        $arr = array();
        foreach($conditions as $condition){
            $carr = [];
            $carr['label'] = $condition->label;
            $carr['value'] = $condition->value;
            $carr['text'] = $condition->text;
            $carr['type'] = $condition->type;
            array_push($arr, $carr);
        }
        return self::partview("taskcondition", array('list' => $arr));
    }

    private function loadTaskpartner($id)
    {
        $task = Task::findOrFail($id);
        return self::partview('taskpartner', ['list' => $task->pkPartners]);
    }
    /*取消任务*/
    public function getRequestCancelPage($taskId){
        $task = Task::findOrFail($taskId);
        if(TaskAction::checkAction($task, TaskAction::REQUESTCANCEL)){
            return view('task.requestcancelpage', ['task'=>$task]);
        }
        return errorPage();
    }
    public function getShowCancelPage($taskId){
        $task = Task::findOrFail($taskId);
        if(TaskAction::checkAction($task, TaskAction::CONFIRMCANCEL) 
            || TaskAction::checkAction($task, TaskAction::UNDOCANCELREQUEST)){
            $cancelRequest = $task->cancelRequest;
            $isProposer = $cancelRequest->user_id == Auth::id();
            $isTaskOwner = $task->user_id == Auth::id();
            return view('task.cancelpage', [
                    'task'=>$task,
                    'cancelRequest'=>$task->cancelRequest,
                    'isProposer'=>$isProposer,
                    'isTaskOwner'=>$isTaskOwner]);
        }elseif(TaskAction::WAITCANCEL){
            return view('task.cancelpage', [
                    'task'=>$task,
                    'cancelRequest'=>new TaskCancelRequest,
                    'isProposer'=>false,
                    'isTaskOwner'=>false]);
        }
        return errorPage();
    }
    public function requestCancel(){
        $taskId =  Input::get('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::REQUESTCANCEL)){
            return errorPage();
        }
        $userId = Auth::id();
        $reason =  Input::get('reason');
        $reasonText =  Input::get('reason_text');
        $pay =  Input::get('pay');
        $cancelRequest = new TaskCancelRequest;
        $cancelRequest->task_id = $taskId;
        $cancelRequest->user_id = $userId;
        $cancelRequest->type = $task->user_id == $userId?TaskCancelRequest::TYPE_A:TaskCancelRequest::TYPE_B;//0, 甲方发起，1,乙方发起，2, 系统发起
        $cancelRequest->status = TaskCancelRequest::STATUS_REQUEST;//1 请求， 2 确认取消，0 放弃取消
        $cancelRequest->pay = ($pay>$task->amount?$task->amount:$pay);
        $cancelRequest->reason = $reason;
        $cancelRequest->reason_text = $reasonText;
        $cancelRequest->save();
        $task->step = TaskStep::CANCELING;
        $task->save();
        return redirect('/task/'.$taskId);
    }
    public function confirmCancel(){
        $taskId =  Input::get('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::CONFIRMCANCEL)){
            return errorPage();
        }
        $requestCancel = $task->cancelRequest;
        if(is_null($requestCancel)){
            return errorPage();
        }
        $task->step = TaskStep::CANCEL;
        $task->save();
        if($requestCancel->pay >0){//处理支付给乙方的补偿
            $gid = GoldManager::findGuaranteeId('task', $taskId);
            $res = GoldManager::guaranteeDealPay($gid, $requestCancel->pay, $task->appointPartner->id, '任务取消，支付乙方的补偿。');
            $res = GoldManager::finishGuaranteeDeal($gid, 3, '任务交付时取消');   
        }
        $requestCancel->status = TaskCancelRequest::STATUS_CONFIRM;
        $requestCancel->finish_date = date('Y-m-d');
        $requestCancel->save();
        return redirect('/task/'.$taskId);
    }
    public function undoCancel(){
        $taskId =  Input::get('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::WAITCANCEL)){
            return errorPage();
        }
        $requestCancel = $task->cancelRequest;
        if(is_null($requestCancel)){
            return errorPage();
        }
        $task->step = TaskStep::DELIVERY;
        $task->save();
        $requestCancel->status = TaskCancelRequest::STATUS_CANCEL;
        $requestCancel->finish_date = date('Y-m-d');
        $requestCancel->save();
        return redirect('/task/'.$taskId);
    }
    public function getFinishPage($taskId){
        $task = Task::findOrFail($taskId);
        if(TaskAction::checkAction($task, TaskAction::FINISH)){
            return view('task.finishtask', ['task'=>$task]);
        }
        return errorPage();
    }
    public function finishTask(){
        $taskId =  Input::get('id');
        $task = Task::findOrFail($taskId);
        if(!TaskAction::checkAction($task, TaskAction::FINISH)){
            return errorPage();
        }
        //结算金币
        $gid = GoldManager::findGuaranteeId('5000123', $taskId);
        $res = GoldManager::guaranteeDealPay($gid, $task->amount, $task->appointPartner->user_id, '任务完成，将佣金支付给乙方');
        $res = GoldManager::finishGuaranteeDeal($gid, 2, '任务完成');   

        //记录用户得分
        $taskScore = new TaskScore;
        $taskScore->task_id = $taskId;
        $taskScore->user_id = $task->appointPartner->user_id;
        $taskScore->creator = Auth::id();
        $taskScore->score1 = Input::get('score');
        $taskScore->save();

        //切换任务状态
        $task->step = TaskStep::FINISH;
        $task->save();
        return redirect('/task/'.$taskId);
    }
    private function loadTasktimeline($id)
    {
        $tls = TaskTimeline::where('task_id', $id)
            ->orderBy('expect_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $arr = array();
        for($i =0;$i<$tls->count();$i++){
            $tl = $tls[$i];
            $time = is_null($tl->finish_date)?$tl->expect_date:$tl->finish_date;
            $text = (is_null($tl->intro)?'':$tl->intro).(is_null($tl->result)?'':$tl->result);
            array_push($arr, ['date'=>$time, 'text'=>$text,'cls'=>$tl->statusClass]);

        }
        return self::partview("tasktimeline", array('list' => $arr));
    }

    //View maps
    public static function taskview($viewName, $objArray = array())
    {
        return view('task.' . $viewName, $objArray);
    }
    public static function partview($viewName, $objArray)
    {
        return view('task.partview.' . $viewName, $objArray);
    }
    public static function actionResult($result, $desc, $action = self::RETURNACTION_NONE)
    {
        $arr = array('res' => $result, 'desc' => $desc, 'action' => $action);
        return response()->json($arr);
    }
    public function getTaskRules()
    {
        $attrs = SysUserSkill::orderBy('hot')->get()->toArray();
        foreach ($attrs as $attr) {
            $code[$attr['code']] = $attr['name'];
        }
        $skill = array(
            array('type' => 'onlyFriend', 'name' => '仅推荐好友', 'key' => 'onlyFriend'), //仅推荐好友
            array('type' => 'userSkill', 'name' => '技能筛选', 'key' => $code), //技能筛选
        );

        return response()->json($skill);
    }

}
