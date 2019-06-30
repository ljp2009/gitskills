<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TaskDelivery;
use App\Models\Task;
use App\Common\TaskAction;
use App\Common\TaskModel;
use App\Common\TaskStep;
use App\Common\CommonUtils;
use Auth;
use Input;
use Redirect;
use DB;

class TaskDeliveryController extends Controller{
	public function showAddDeliveryPage($taskId){
        $task = Task::findOrFail($taskId);
        $actions = TaskAction::getActions($task);
        if(TaskAction::checkAction($actions, TaskAction::DELIVERY)){
            return view('task.showtaskdelivery',
               ['id'=>0,
               'taskId'=>$taskId,
               'images'=>[],
               'attachments'=>[],
               'text'=>'', ]);
        }
        return errorPage();
	}
    public function showEditDeliveryPage($deliveryId){
        $delivery = TaskDelivery::findOrFail($deliveryId);
        if(!$this->checkDeliveryAuth($delivery)){
            return errorPage();
        }
        $actions = TaskAction::getActions($delivery->task);
        if(!TaskAction::checkAction($actions, TaskAction::DELIVERY)){
            return errorPage();
        }
        $images = [];
        foreach($delivery->image as $img){
            $images[$img->originName] = $img->getPath();
        }
        return view('task.showtaskdelivery',
           ['id'=>$delivery->id,
           'taskId'=>$delivery->task_id,
           'images'=>$images,
           'attachments'=>$delivery->attachments,
           'text'=>$delivery->text,
       ]);
    }
    public function deleteDelivery(){
        $delivery = TaskDelivery::findOrFail($deliveryId);
        if(!$this->checkDeliveryAuth($delivery)){
            return response()->json(['res'=>false, 'info'=>'交付已锁定，无法删除。']);
        }
        $actions = TaskAction::getActions($delivery->task);
        if(!TaskAction::checkAction($actions, TaskAction::DELIVERY)){
            return response()->json(['res'=>false, 'info'=>'无操作权限。']);
        }
        $delivery->delete();
        return true;

    }
    private function checkDeliveryAuth($delivery){
        if($delivery->user_id != Auth::id()){
            return false;
        }
        return !$delivery->isLocked; 
    }
	public function addDelivery(){
		$taskId = Input::get('taskid');
		$text = CommonUtils::escapeSpecialChars(Input::get('text'));
		$image = Input::get('image');
		$attachments = Input::get('attachments');
		$taskDelivery = new TaskDelivery;
		$taskDelivery->task_id = $taskId;
		$taskDelivery->user_id = Auth::user()->id;
		$taskDelivery->image = $image;
		$taskDelivery->text = $text;
		$taskDelivery->attachment =$this->formatAttach($attachments);
		$taskDelivery->save();
		return redirect('/task/' . $taskId);
	}
	public function editDelivery(){
        $deliveryId = Input::get('id');
        $delivery = TaskDelivery::findOrFail($deliveryId);
        if(!$this->checkDeliveryAuth($delivery)){
            return errorPage();
        }
        $actions = TaskAction::getActions($delivery->task);
        if(!TaskAction::checkAction($actions, TaskAction::DELIVERY)){
            return errorPage();
        }
        $images = Input::get('image');
		$attachments = Input::get('attachments');
		$text = CommonUtils::escapeSpecialChars(Input::get('text'));
		$delivery->image = $images;
		$delivery->text = $text;
		$delivery->attachment =$this->formatAttach($attachments);
		$delivery->save();
		return redirect('/task/' . $delivery->task_id);
	}
    private function formatAttach($attachments){
       $attachList =  explode(';', $attachments);    
       $value = '';
       foreach($attachList as $attach){
           $infoArr = explode(':', $attach);
           if(count($infoArr) != 3){
               continue;//过滤无效内容
           }
           $value .= ($attach.';');
       }
       return $value;
    }
    public function showDeliveryList($page, $taskId){
        return view('task.deliverylist', 
            ['title'=>'交付列表', 'resource'=>'task_delivery', 
             'type'=>'taskdelivery',
            'listName'=>'default', 'id'=>$taskId,'page'=>$page]);
    }
    public function showDeliveryData($from, $to, $taskId){

        $task = Task::findOrFail($taskId);
        $actions = TaskAction::getActions($task);
        $deliverys = [];
        if(TaskAction::checkAction($actions, TaskAction::DELIVERY)){
            $deliverys = TaskDelivery::where('task_id', $taskId)
                ->where('user_id', Auth::id())
                ->orderBy('id', 'desc')
                ->skip($from)->take($to-$from+1)
                ->get();
        }elseif(TaskAction::checkAction($actions, TaskAction::VIEWDELIVERY)){
            $deliverys = TaskDelivery::where('task_id', $taskId)
                ->orderBy('id', 'desc')
                ->skip($from)->take($to-$from+1)
                ->get();
        }
        return view('task.deliverylistitem', ['models'=>$deliverys]);
    }
    public function showDeliveryPartview($taskId){
        $task = Task::findOrFail($taskId);
        if($task->model != TaskModel::PK){
            return errorPage();
        }
        if($task->step != TaskStep::REVIEW && $task->step != TaskStep::FINISH){
            return errorPage();
        }
        $deliverys = TaskDelivery::where('task_id', $taskId)->get();
        return view('task.partview.taskdelivery', ['models'=>$deliverys]);
    }
}


