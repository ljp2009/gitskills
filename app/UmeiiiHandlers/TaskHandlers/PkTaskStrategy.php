<?php

namespace App\UmeiiiHandlers\TaskHandlers;
use App\Models\Task;
use App\Http\Requests;

class PkTaskStrategy extends TaskStrategy
{
    protected function getCreatePage(){
        return view('task.createpk');
    }
    protected function bindCreateData(Request $request){
        $this->_task->type = Task::TASKTYPE_PK;
        $this->_task->max_partner_count = $request->max_partner_count;
        $this->_task->assign_solution = $request->assign_solution;
    }
}
