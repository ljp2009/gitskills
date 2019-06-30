<?php

use Illuminate\Database\Seeder;
use App\Model\Task;
use App\Common\TaskModel;
use App\Common\TaskStep;
class PkTaskTestData extends Seeder
{
    const TESTFLAG = '~~test~~';
    const OWNER    = 2;
    private $tmpImg = [];
    private $tmpLongText = [];
    private $tmpShotText = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Init PkTaskTestData');
    }
    public function createTask(){
        $tast = Task;      
        $task->title = self::TESTFLAG.'测试任务' ;
        $task->user_id = self::OWNER;
        $task->amount = 10000;
        $task->pay_type = 'coin';
        $task->is_crowdfunding = false;
        $task->step = 1;
        $task->delivery_date = $request->input('delivery_date');
        $task->delivery_type = 1;//目前仅处理线上交付行为
        if($taskMode == 'pk'){
            $task->model = TaskModel::PK;
            $task->max_partner_count = 0;
            $task->assign_solution = $request->input('assign_solution');
    }
    private function getTestImage(){ }
    private function getTestLongText(){}
    private function getTestShotText(){}
    private function getTestUser(){}
}
