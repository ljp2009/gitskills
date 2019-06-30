<?php
namespace App\Common;
use Auth;
class TaskAction
{
    const VIEW = 3000;//查看
    const PREVIEW = 1000;//预览
    const CREATE = 1001;//创建
    const MODIFY = 1002;//修改
    const DELETE = 1003;//删除
    const PUBLISH = 1004;//发布
    const REQUESTJOIN = 2001;//申请加入
    const AGREEJOIN = 1005;//同意加入
    const UNDOAGREE = 1105;//撤回同意加入
    const REJECTJOIN = 1006;//拒绝加入
    const UNDOREJECT = 1106;//撤回拒绝加入
    const CONFIRMJOIN = 2002;//确认加入(乙方)
    const WAITAGREE = 2003;//等待同意加入
    const BEREJECTED = 2004;//被拒绝了
    const VIEWMILESTONE = 3007;//查看里程碑
    const SIGNINA = 1007;//甲方里程碑签到
    const SIGNINB = 2007;//乙方里程碑签到
    const CANCEL = 1008;//没有进入交付阶段的约定任务取消
    const FINISH = 1009;//任务结束
    const INVITE = 1010;//邀请
    const DELIVERY = 2011;//任务交付（乙方）
    const VIEWDELIVERY = 1011;//任务交付（乙方）
    const REQUESTCANCEL = 3001;//取消任务请求
    const UNDOCANCELREQUEST = 3101;//撤回取消请求
    const WAITCANCEL = 3002;//等待确认取消
    const CONFIRMCANCEL = 3201;//确认取消
    const LOGIN = 3099;//登录
    private $action = self::VIEW;
    public static function getActions($task){
        $step = new TaskStep($task);
        $model = new TaskModel($task);
        $stepA = $step->getActions(); 
        $modelA = $model->getActions(); 
        $role = TaskRole::getRole($task);
        $roleA = $role->getActions();
      
        $actions0 = array_intersect($stepA, $roleA);

        $actions = array_intersect($modelA, $actions0);
        //移除PK任务发布后的权限
        if($task->step != TaskStep::CREATED && $task->model == TaskModel::PK){
          $tmp = array_flip($actions);
          unset($tmp[TaskAction::MODIFY]);
          $actions = array_flip($tmp);
        }
        //处理约定任务取消过程中用户的动作权限
        if(in_array(TaskAction::WAITCANCEL, $actions)){
            if(is_null($task->cancelRequest)){//针对非用户发起的取消流程的保护
              $tmp = array_flip($actions);
              unset($tmp[TaskAction::CONFIRMCANCEL]);
              unset($tmp[TaskAction::UNDOCANCELREQUEST]);
              $actions = array_flip($tmp);
            }else{
                if($task->cancelRequest->user_id == Auth::id()){
                  $tmp = array_flip($actions);
                  unset($tmp[TaskAction::CONFIRMCANCEL]);
                  $actions = array_flip($tmp);
                }
                else{
                  $tmp = array_flip($actions);
                  unset($tmp[TaskAction::UNDOCANCELREQUEST]);
                  $actions = array_flip($tmp);
                }
            }
        }

        if(isset($_GET['debug']) && $_GET['debug']=='true'){
            echo '========阶段动作('.$step->getName().')=============';
            echo '<pre>'; 
            foreach($stepA  as $act){
                $tmp=self::getActionName($act); 
                var_dump($tmp);
            }
            echo '========模式动作=============';
            foreach($modelA  as $act){
                $tmp=self::getActionName($act); 
                var_dump($tmp);
            }
            echo '========角色动作('.$role->getName().')=============';
            
            foreach($roleA  as $act){
                $tmp=self::getActionName($act); 
                var_dump($tmp);
            }
            echo '========最终动作=============';
            foreach($actions  as $act){
                $tmp=self::getActionName($act); 
                var_dump($tmp);
            }
            echo '</pre>'; 
            die();
        }
        return $actions;
    }
    public static function checkAction($input, $action){
        $actions = [];
        if(is_array($input)){//输入通过GetAciton获得的Array
            $actions = $input;
        }
        if(is_object($input)){//输入的Task对象
            $actions = self::getActions($input);
        }
        return in_array($action, $actions);
    }
    public static function getActionName($action){
        $res = ['code'=>$action, 'name'=>''];
        switch($action){
        case self::VIEW:
            $res['name'] = "查看";
            break;
        case self::PREVIEW:
            $res['name'] = "预览";
            break;
        case self::CREATE:
            $res['name'] = "创建";
            break;
        case self::MODIFY:
            $res['name'] = "修改";
            break;
        case self::DELETE:
            $res['name'] = "删除";
            break;
        case self::PUBLISH:
            $res['name'] = "发布";
            break;
        case self::REQUESTJOIN:
            $res['name'] = "申请加入";
            break;
        case self::AGREEJOIN:
            $res['name'] = "同意加入";
            break;
        case self::UNDOAGREE:
            $res['name'] = "撤回同意加入";
            break;
        case self::REJECTJOIN:
            $res['name'] = "拒绝加入";
            break;
        case self::UNDOREJECT:
            $res['name'] = "撤回拒绝加入";
            break;
        case self::CONFIRMJOIN:
            $res['name'] = "确认加入(乙方)";
            break;
        case self::WAITAGREE:
            $res['name'] = "等待同意加入";
            break;
        case self::BEREJECTED:
            $res['name'] = "被拒绝了";
            break;
        case self::VIEWMILESTONE:
            $res['name'] = "查看里程碑";
            break;
        case self::SIGNINA:
            $res['name'] = "甲方里程碑签到";
            break;
        case self::SIGNINB:
            $res['name'] = "乙方里程碑签到";
            break;
        case self::CANCEL:
            $res['name'] = "没有进入交付阶段的约定任务取消";
            break;
        case self::FINISH:
            $res['name'] = "任务结束";
            break;
        case self::INVITE:
            $res['name'] = "邀请";
            break;
        case self::DELIVERY:
            $res['name'] = "任务交付（乙方）";
            break;
        case self::VIEWDELIVERY:
            $res['name'] = "查看任务交付";
            break;
        case self::REQUESTCANCEL:
            $res['name'] = "取消任务请求";
            break;
        case self::UNDOCANCELREQUEST:
            $res['name'] = "撤回取消请求";
            break;
        case self::WAITCANCEL:
            $res['name'] = "等待确认取消";
            break;
        case self::CONFIRMCANCEL:
            $res['name'] = "确认取消";
            break;
        case self::LOGIN:
            $res['name'] = "登录";
            break;
        }
        return $res;
    }

}
