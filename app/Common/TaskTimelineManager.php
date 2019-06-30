<?php
namespace App\Common;
use Config;
use App\Models as MD;
use App\Models\Task;
use App\Models\TaskTimeline;
use App\Models\TaskParter as TP;
use Auth;
class TaskTimelineManager
{
    const PUBLISHTASK = 0;
    const FIRSTREQUEST = 1;
    const AGREEPARTNER = 2;
    const MAXPARTNER = 3;
    const CONFIRMTASK = 4;
    const CANCELTASK = 5;
    public static function addInfo($type, $task,$partner = null){
        $tl = new TaskTimeline();
        $tl->user_id = Auth::check()?Auth::user()->id:0;
        $tl->task_id = $task->id;
        $tl->expect_date = date('Y-m-d');
        $tl->finish_date = date('Y-m-d');
        $tl->type = TaskTimeline::TTTYPE_SYSTEM;
        $tl->status = TaskTimeline::TTSTATUS_FINISH;
        $fun = "getIntro".$type;
        $tl->intro = self::$fun($task, $partner);
        $tl->save();
    }
    //以下是根据type确定的时间线描述
    private static function getIntro0($task, $user){
        return $user->display_name."发布了任务。";
    }
    private static function getIntro1($task, $user){
        return $user->display_name."第一个申请参与此任务。";
    }
    private static function getIntro2($task, $user){
        if($task->task_type == Task::TASKTYPE_APPOINT){
            return "创建者同意由".$user->display_name."来完成这个任务，任务进入了交付阶段。";
        }
        else{
            return "创建者同意".$user->display_name."参与完成这个任务。";
        }
    }
    private static function getIntro3($task, $user){
        return "参与者已经满员，任务进入交付阶段。";
    }
    private static function getIntro4($task, $user){
        return $user->display_name."确认这个任务已经完成了。";
    }
    private static function getIntro5($task, $user){
        return $user->display_name."取消了这个任务。";
    }
    private static function getIntro6($task, $user){}
    private static function getIntro7($task, $user){}
    private static function getIntro8($task, $user){}
    private static function getIntro9($task, $user){}
}
