<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\TaskPartner;
use App\Models\TaskMilestone;
use App\Models\TaskDelivery;
use App\Common\TaskStep;
use App\Common\TaskAction;
use App\Common\TaskModel;
use App\Common\TaskRole;
use App\Common\TaskPartnerStatus;
use Illuminate\Http\Request;

class TaskTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->clearData();
//        $this->createAppointTasks();
//        $this->createPkTasks();
          $task = $this->createAPKTask('KKK');
          $this->createPKTaskDelivery($task);
    }
	private function clearData()
	{
         DB::table('t_task')->truncate();
         DB::table('t_task_parter')->truncate();
         DB::table('t_task_timeline')->truncate();
         DB::table('t_task_milestone')->truncate();
         DB::table('t_task_condition')->truncate();
    }
    private function createAppointTasks()
    {
        for($i=0; $i<8; $i++){
            $step = TaskStep::make($i);
            $task = new Task;
            $task->user_id = 2;
            $task->title =  sprintf("%04d创建{%s}阶段的任务", $i,$step->getName());
            $task->intro = "关于创建阶段的任务的简介。";
            $task->model = TaskModel::APPOINT; 
            $task->step = TaskStep::make($i)->step;
            $task->delivery_date =date('Y-m-d', strtotime("+1 months"));
            $task->publish_date =date('Y-m-d');
            $task->skill_type='2001008';
            $task->amount = 5000;
            $task->save();
            if($i == 2){
                $this->createPartners($task->id);
                $this->createMilestones($task->id);
            }
            if($i == 3){
                $this->createPartners($task->id, 3);
                $this->createMilestones($task->id);
            }
            if($i == 4){
                $this->createPartners($task->id, 3, 1);
                $this->createMilestones($task->id);
            }
        }
        
    }
    //初始化PK任务
    private function createPkTasks()
    {
        for($i=0; $i<8; $i++){
            $step = TaskStep::make($i);
            $task = new Task;
            $task->user_id = 2;
            $task->title =  sprintf("%04d创建{%s}阶段的任务", $i,$step->getName());
            $task->intro = "关于创建阶段的任务的简介。";
            $task->model = TaskModel::PK; 
            if($i%2==0){
                //复数创建交付任务
                $task->step = TaskStep::DELIVERY;
            }else{
                //单数创建审查任务
                $task->step = TaskStep::REVIEW; 
            }
            $task->amount = 5000;
            $task->delivery_date =date('Y-m-d', strtotime("+1 months"));
            $task->publish_date =date('Y-m-d');
            $task->skill_type='2001008';
            $task->save();
        }
    }
    private function createPartners($taskId, $joinCt=0, $ptCt=0, $ct=10){
        $jct = 0;
        $pct = 0;
        for($i=0; $i<$ct; $i++) {
            $partner = new TaskPartner;
            $partner->user_id = $i+3;
            $partner->task_id = $taskId;
            if($jct<$joinCt){
                $partner->status = TaskPartnerStatus::JOININ;
                $jct++;
            }elseif($pct < $ptCt){
                $partner->status = TaskPartnerStatus::PARTNER;
                $pct++;
            }else{
                $partner->status = TaskPartnerStatus::REQUEST;
            }
            $partner->request_description = '';
            $partner->reject_result = '';
            $partner->image = '';
            $partner->save();
        }
    }
    private function createMilestones($taskId, $ct=5){
        for($i=0; $i<$ct; $i++){
            $mileStone = new TaskMilestone;
            $mileStone->task_id = $taskId;
            $mileStone->date = date('Y-m-d', strtotime("+".$i." days"));
            $mileStone->text = "任务".$taskId."的里程碑".$i;
            $mileStone->status = 'wait';
            $mileStone->save();
        }
    }
    private function createAPKTask($name){
        $fakeRequest = new Request;
        $task = new Task;
        $task->user_id = 2;
        $task->title =  sprintf("测试任务[{%s}]", $name);
        $task->intro = "关于创建阶段的任务的简介。";
        $task->model = TaskModel::PK; 
        $task->step = TaskStep::DELIVERY;
        $task->amount = 5000;
        $task->delivery_date =date('Y-m-d', strtotime("+1 months"));
        $task->publish_date =date('Y-m-d');
        $task->skill_type='2001008';
        $task->assign_solution = 1;
        $task->save();
        return $task;
    }
    private function createPKTaskDelivery($task){
        $imageList = [
            'deliveryimg/def-1477042690348-0afqr8fzjjor.jpg;',
            'deliveryimg/def-1477275338035-ovbipq77gb9.jpg;',
            'image-1468839025-Hxtcd0.jpeg;image-1468839025-Hxtcd1.png;',
            'image-1468838917-f0i730.jpeg;',
            'image-1468838880-z3A9n0.jpeg;'
        ];
        for($i=3;$i<13;$i++){
            $taskDelivery = new TaskDelivery;
            $taskDelivery->task_id = $task->id;
            $taskDelivery->user_id = $i;
            $taskDelivery->image = $imageList[$i%5];
            $taskDelivery->text = sprintf("%04d交付物", $i); 
            $taskDelivery->save();
            $taskPartner = new TaskPartner;
            $taskPartner->task_id = $task->id;
            $taskPartner->user_id = $i;
            $taskPartner->status = TaskPartnerStatus::PARTNER;
            $taskPartner->save();
        }
    }
}
