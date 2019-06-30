<?php
namespace App\Common;
use Config;
use App\Models as MD;
use App\Models\Task;
use App\Models\TaskTimeline;
use App\Models\TaskPartner;
use App\Common\TaskStep;
use Auth;
class TaskRole
{
    const NONE = 0;
    const OWNER = 1;
    const VISITOR = 2;
    const REQUESTER = 3;
    const WAITCHOICE = 4;
    const PARTNER = 5;
    const OUTER = 6;
    const NOTLOGIN = 7;

    private $task = null;
    private $userId = 0;
    private $taskRole = self::NONE;
    public function __construct($task, $userId){
        $this->task = $task; 
        $this->userId = $userId;
        $this->taskRole = $this->checkTaskRole();
    }
    
    public function __get($value){
        if($value == 'role'){
            return $this->taskRole;
        }
    }
    public function getName(){
        switch($this->taskRole){
        case self::NONE:
            return '无效';
        case self::OWNER:
            return '创建者';
        case self::VISITOR:
            return '访客';
        case self::REQUESTER:
            return '申请者';
        case self::WAITCHOICE:
            return '待确认合作者';
        case self::PARTNER:
            return '合作者';
        case self::OUTER:
            return '出局者';
        case self::NOTLOGIN:
            return '未登录';
        }
    }
    public function getActions(){
        $arr = [];
        switch($this->taskRole){
        case self::OWNER:
            $arr = [
                TaskAction::VIEW,
                TaskAction::PREVIEW,
                TaskAction::CREATE,
                TaskAction::MODIFY,
                TaskAction::DELETE,
                TaskAction::PUBLISH,
                TaskAction::AGREEJOIN,
                TaskAction::UNDOAGREE,
                TaskAction::REJECTJOIN,
                TaskAction::UNDOREJECT,
                TaskAction::SIGNINA,
                TaskAction::CANCEL,
                TaskAction::FINISH,
                TaskAction::REQUESTCANCEL,
                TaskAction::UNDOCANCELREQUEST,
                TaskAction::CONFIRMCANCEL,
                TaskAction::WAITCANCEL,
                TaskAction::INVITE,
                TaskAction::VIEWDELIVERY,
            ];
            break;
        case self::VISITOR:
            $arr = [
                TaskAction::VIEW,
                TaskAction::REQUESTJOIN,
            ];
            break;
        case self::REQUESTER:
            $arr = [
                TaskAction::VIEW,
                TaskAction::WAITAGREE,
            ];
            break;
        case self::WAITCHOICE:
            $arr = [
                TaskAction::VIEW,
                TaskAction::CONFIRMJOIN,
            ];
            break;
        case self::PARTNER:
            $arr = [
                TaskAction::VIEW,
                TaskAction::DELIVERY,
                TaskAction::VIEWDELIVERY,
                TaskAction::REQUESTCANCEL,
                TaskAction::UNDOCANCELREQUEST,
                TaskAction::CONFIRMCANCEL,
                TaskAction::SIGNINB,
                TaskAction::WAITCANCEL,
            ];
            break;
        case self::OUTER:
            $arr = [
                TaskAction::VIEW,
                TaskAction::BEREJECTED,
            ];
            break;
        case self::NOTLOGIN:
            $arr = [
                TaskAction::VIEW,
                TaskAction::LOGIN,
            ];
            break;
        }
        return $arr;
    }
    private function checkTaskRole(){
        $task = $this->task;
        $userId = $this->userId;
        //无参与者介入
        if($task->user_id == $userId) return self::OWNER; //任务创建者
        if(in_array($task->step, [TaskStep::CREATED])){
            return self::NONE; //非创建者在任务发布前无任何角色
        }
        if($userId == 0) return self::NOTLOGIN; //未登录
        //有参与者介入 
        $partner = TaskPartner::where('task_id', $task->id) 
            ->where('user_id', $userId)->first();
        if(is_null($partner)) return self::VISITOR;//未参与的访客
        if(in_array($task->step, [TaskStep::PUBLISHED])){
            switch($partner->status){
                case TaskPartnerStatus::REJECT:
                    return self::OUTER;//出局者
                case TaskPartnerStatus::REQUEST:
                    return self::REQUESTER;//申请者
                default:
                    return self::NONE;//异常
            }
        }
        if(in_array($task->step, [TaskStep::CHOICING])){
            switch($partner->status){
                case TaskPartnerStatus::REJECT:
                    return self::OUTER;//出局者
                case TaskPartnerStatus::REQUEST:
                    return self::REQUESTER;//申请者
                case TaskPartnerStatus::JOININ:
                    return self::WAITCHOICE; //待确认的备选者
                case TaskPartnerStatus::PARTNER:
                    return self::PARTNER; //合作者
                default:
                    return self::NONE;//异常
            }
        }
        //交付和交付后审查阶段
        if(in_array($task->step, [
            TaskStep::DELIVERY,
            TaskStep::CANCELING,
            TaskStep::REVIEW,
            TaskStep::FINISH,
            TaskStep::CANCEL])){
            if($partner->status == TaskPartnerStatus::PARTNER){
                return self::PARTNER; //合作者
            }else{
                return self::VISITOR; //除去合作者之外都是访客
            }
        }
    }
    //--static functions-- 
    public static function getRole($t, $userId = 0){
        if($userId == 0) $userId = Auth::check()?Auth::id():0;

        if(is_object($t)) {
           return new TaskRole($t, $userId);
        }else{
           $task = Task::findOrFail($t, $userId);
           return new TaskRole($task);
        }
    }
}
