<?php
namespace App\Common;
class TaskModel
{
    const NONE = 'none'; //约定任务
    const APPOINT = 'simple'; //约定任务
    const PK      = 'tenders'; //pk任务
    private $model =self::NONE;
    public function __construct($task){
        $this->model = $task->model;
    }
    public function __tostring(){
        return $this->getName();
    }
    public function __get($value){
        if($value == 'model'){
            return $this->model;
        }
        if($value == 'name'){
            return $this->getName;
        }
    }
    public function getName(){
        switch($this->model){
        case self::APPOINT:
            return '约定任务';
        case self::PK:
            return 'PK任务';
        default:
            return '异常';
        }
    }
    public function getActions(){
        $arr = [];
        switch($this->model){

        case self::NONE:
            $arr = [];
            break;
        case self::APPOINT:
            $arr = [
                TaskAction::VIEW,
                TaskAction::PREVIEW,
                TaskAction::CREATE,
                TaskAction::MODIFY,
                TaskAction::DELETE,
                TaskAction::PUBLISH,
                TaskAction::REQUESTJOIN,
                TaskAction::AGREEJOIN,
                TaskAction::UNDOAGREE,
                TaskAction::REJECTJOIN,
                TaskAction::UNDOREJECT,
                TaskAction::WAITAGREE,
                TaskAction::CONFIRMJOIN,
                TaskAction::VIEWMILESTONE,
                TaskAction::SIGNINA,
                TaskAction::SIGNINB,
                TaskAction::CANCEL,
                TaskAction::DELIVERY,
                TaskAction::VIEWDELIVERY,
                TaskAction::FINISH,
                TaskAction::REQUESTCANCEL,
                TaskAction::UNDOCANCELREQUEST,
                TaskAction::CONFIRMCANCEL,
                TaskAction::LOGIN,
                TaskAction::WAITCANCEL,
                TaskAction::BEREJECTED,
                TaskAction::INVITE,
            ];
            break;
        case self::PK:
            $arr = [
                TaskAction::VIEW,
                TaskAction::PREVIEW,
                TaskAction::CREATE,
                TaskAction::MODIFY,
                TaskAction::DELETE,
                TaskAction::PUBLISH,
                TaskAction::REQUESTJOIN,
                TaskAction::AGREEJOIN,
                TaskAction::REJECTJOIN,
                TaskAction::UNDOREJECT,
                TaskAction::DELIVERY,
                TaskAction::VIEWDELIVERY,
                TaskAction::WAITAGREE,
                TaskAction::LOGIN,
                TaskAction::BEREJECTED,
                TaskAction::INVITE,
            ];
            break;
        }
        return $arr;
    }
    public static function make($task){
        return new TaskModel($task);
    }
}
