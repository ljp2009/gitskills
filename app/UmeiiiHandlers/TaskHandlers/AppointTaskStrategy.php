<?php

namespace App\UmeiiiHandlers\TaskHandlers;
use App\Models\Task;
use App\Http\Requests;

class AppointTaskStrategy extends TaskStrategy
{
    public function getCreatePage(){
        return view('task.createappoint');
    }
    public function bindCreateData(Request $request){
        $this->_task->type = Task::TASKTYPE_APPOINT;
    }
}
