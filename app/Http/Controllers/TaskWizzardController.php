<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UmeiiiHandlers\TaskHandlers\TaskStrategy;
use App\Models\Task;
use App\Common\TaskModel;
use Illuminate\Http\Response;
use Auth, Input;
class TaskWizzardController extends Controller
{
    public function showCreate($taskMode) {
        return view('task.createnew', ['taskMode'=>$taskMode]);
    }

    public function createBase(Request $request, $taskMode){
        $task = new Task;
        $task->title = $request->input('title');
        $task->user_id = auth::id();
        $task->amount = $request->input('amount');
        $task->pay_type = 'coin';
        $task->is_crowdfunding = false;
        $task->step = 1;
        $task->delivery_date = $request->input('delivery_date');
        $task->delivery_type = 1;//目前仅处理线上交付行为
        if($taskMode == 'pk'){
            $task->model = TaskModel::PK;
            $task->max_partner_count = 0;
            $task->assign_solution = $request->input('assign_solution');
        }elseif($taskMode == 'appoint'){
            $task->model = TaskModel::APPOINT;
        }
        $request->session()->put('tmpTask', $task);
        return view('task.editrequirement', ['id'=>0,'taskMode'=>$taskMode, 'isCreate' =>true]);
    }
    public function createRequirement(Request $request, $taskMode){
        $task = $request->session()->get('tmpTask');
        $task->intro = $request->input('taskIntro');
        $task->image = $request->input('taskImg');
        $task->skill_type = $request->input('skillType');
        $task->review_img = $request->input('reviewImg');
        $task->review_intro = $request->input('reviewIntro');
        $request->session()->put('tmpTask', $task);
        return view('task.editfilter', ['id'=>0,'taskMode'=>$taskMode, 'isCreate' =>true]);
    }
    public function createUserFilter(Request $request, $taskMode){
        $task = $request->session()->get('tmpTask');
        if($request->skillLevel>0){
            $task->skill_level = $request->input('skillLevel');
        }
        $task->credit_level = $request->input('creditLevel');
        $task->save();
        $request->session()->forget('tmpTask');
        return redirect(noBackUrl('/pubtask/manage-main/'.$task->id, 4));
    }
    public function createConditions(Request $request){
        return view('task.editmilestone', ['id'=>0,'taskMode'=>$taskMode, 'isCreate' =>true]);
    }
    public function createMilestones(Request $request){
        return view('task.createfinish', ['id'=>0,'taskMode'=>$taskMode, 'isCreate' =>true]);
    }
}

