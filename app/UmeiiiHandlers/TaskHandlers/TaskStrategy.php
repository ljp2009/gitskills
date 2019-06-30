<?php

namespace App\UmeiiiHandlers\TaskHandlers;

use App\Models\Task;
use App\Http\Requests;
use auth, Request;

abstract class TaskStrategy
{
    protected $_taskId = 0;
    protected $_task = null;
    protected $_type = null;
    public function __construct($id){


    }
    /*** static functions ***/
    public static function createStrategy($type, $id){
        if($type == 'pk')
            return new PkTaskStrategy($id);
        if($type == 'appoint')
            return new AppointTaskStrategy($id);
        else
            return null;
    }
    /*** public functions***/
    public function bind($id){
        $this->_taskId = $id;
    }
    public function showCreate(){
       return $this->getCreatePage(); 
    }
    public function saveCreate(Request $request){
        $this->_task = new Task;
        $this->_task->name = $request->name;
        $this->_task->user_id = auth::id();
        $this->_task->amout = $request->amount;
        $this->_task->pay_type = 'coin';
        $this->_task->is_crowdfunding = false;
        $this->_task->step = 1;
        $this->_task->delivery_date = $request->delivery_date;
        $this->_task->delivery_type = 1;//目前仅处理线上交付行为
        $this->bindCreateData($request);
        $this->_task->save(); 
        return view('task.updatecontent', ['id'=>$this->_task->id, 'isCreate' =>true]);
    }
    public function saveCreateContent(Request $request){
        $this->saveContent($request);
        return view('task.userfilter', ['id'=>$this->_task->id, 'isCreate'=>true]);
    }
    public function saveUpdateContent(Request $request){
        $this->saveContent($request);
        return redirect($this->_task->getDetailUrl());
    }
    private function saveContent(Request $request){
        
    }

    /*** abstract functions ***/
    abstract protected function getCreatePage(); //显示创建页面
    abstract protected function bindCreateData(Request $request);//保存任务
}
