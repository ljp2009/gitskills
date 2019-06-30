<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Image;
use Input, Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimeline;
use App\Models\TaskDeliverCondition;
//任务管理
class TaskController extends Controller
{

    public function getList($sort='', $step=''){


        //状态.全部
        if($step == ''){
            if ($sort == '1') {
                # code...
                $items = Task::orderBy('delivery_date', 'asc')->paginate(15);
            } else {
                $items = Task::orderBy('delivery_date', 'desc')->paginate(15);
            }
        //状态.其他
        } else {
            if ($sort == '1') {
                # code...
                $items = Task::where('step', $step)->orderBy('delivery_date', 'asc')->paginate(15);
            } else {
                $items = Task::where('step', $step)->orderBy('delivery_date', 'desc')->paginate(15);
            }
        }
        
        return view('admins.pages.tasklist',['items'=>$items,'sort'=>$sort,'step'=>$step]);
    }

    //删除任务
    public function postDelete(){
        $id      = Input::get('id');
        $task    = Task::findOrFail($id);

        TaskTimeline::where('task_id', $id)->delete();
        TaskDeliverCondition::where('task_id', $id)->delete();
        $task->delete();
        return response()->json(['res'=>true, 'id'=>$id]);

    }

    
}
