<?php
namespace App\Common;
class TaskStep
{
    const CREATED = 1;
    const PUBLISHED = 2;
    const CHOICING = 3;
    const DELIVERY = 4;
    const REVIEW = 5;
    const REVIEW_1 = 5;
    const REVIEW_2 = 6;
    const FINISH = 7;
    const CANCELING = 9;
    const CANCEL = 0;
    private $step = self::CANCEL;
    public function __construct($task){
        if(is_integer($task)){
            $this->step = $task; 
        }else{
            $this->step = $task->step; 
        }
    }
    public function __tostring(){
        return $this->step;
    }
    public function __get($value){
        if($value == 'step'){
            return $this->step;
        }
        if($value == 'name'){
            return $this->getName();
        }
    }
    public function getName(){
        $name = '';
        switch($this->step){
        case self::CREATED:
            $name = '待发布';
            break;
        case self::PUBLISHED:
            $name = '招募中';
            break;
        case self::CHOICING:
            $name = '招募确认中';
            break;
        case self::DELIVERY:
            $name = '交付中';
            break;
        case self::REVIEW_1:
            $name = '第一轮评审中';
            break;
        case self::REVIEW_2:
            $name = '第二轮评审中';
            break;
        case self::FINISH:
            $name = '已经完成';
            break;
        case self::CANCELING:
            $name = '申请取消中';
            break;
        case self::CANCEL:
            $name = '已经取消';
            break;
        default:
            $name = '无效状态';
        }
        return $name;
    }
    public function getActions(){
        $arr = [];
        switch($this->step){
        case self::CREATED:
            $arr = [
                TaskAction::PREVIEW,
                TaskAction::MODIFY,
                TaskAction::DELETE,
                TaskAction::PUBLISH,
            ];
            break;
        case self::PUBLISHED:
            $arr = [
                TaskAction::VIEW,
                TaskAction::MODIFY,
                TaskAction::AGREEJOIN,
                TaskAction::UNDOAGREE,
                TaskAction::REQUESTJOIN,
                TaskAction::REJECTJOIN,
                TaskAction::UNDOREJECT,
                TaskAction::CANCEL,
                TaskAction::LOGIN,
                TaskAction::WAITAGREE,
                TaskAction::BEREJECTED,
                TaskAction::INVITE,
            ];
            break;
        case self::CHOICING:
            $arr = [
                TaskAction::VIEW,
                TaskAction::REQUESTJOIN,
                TaskAction::AGREEJOIN,
                TaskAction::UNDOAGREE,
                TaskAction::REJECTJOIN,
                TaskAction::UNDOREJECT,
                TaskAction::CONFIRMJOIN,
                TaskAction::CANCEL,
                TaskAction::LOGIN,
                TaskAction::DELIVERY,
                TaskAction::WAITAGREE,
                TaskAction::BEREJECTED,
                TaskAction::INVITE,
                TaskAction::VIEWDELIVERY,
            ];
            break;
        case self::DELIVERY:
            $arr = [
                TaskAction::VIEW,
                TaskAction::SIGNINA,
                TaskAction::SIGNINB,
                TaskAction::DELIVERY,
                TaskAction::REQUESTCANCEL,
                TaskAction::FINISH,
                TaskAction::LOGIN,
                TaskAction::VIEWDELIVERY,
                TaskAction::VIEWMILESTONE,
            ];
            break;
        case self::REVIEW_1:
            $arr = [
                TaskAction::VIEW,
            ];
            break;
        case self::REVIEW_2:
            $arr = [
                TaskAction::VIEW,
            ];
            break;
        case self::FINISH:
            $arr = [
                TaskAction::VIEW,
            ];
            break;
        case self::CANCELING:
            $arr = [
                TaskAction::VIEW,
                //TaskAction::REQUESTCANCEL,
                TaskAction::WAITCANCEL,
                TaskAction::CONFIRMCANCEL,
            ];
            break;
        case self::CANCEL:
            $arr = [
                TaskAction::VIEW,
                TaskAction::DELETE,
            ];
            break;

        }
        return $arr;
    }
    public function getStatus(){
        return new TaskPartnerStatus;
    }
    public function getStepName($task){
    }
    public static function make($task){
        return new TaskStep($task);
    }
}
