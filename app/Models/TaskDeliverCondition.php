<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDeliverCondition extends Model
{
    protected $table     = 't_task_deliver_condition';
    protected $guarded   = ['id'];
    public function getTypeNameAttribute(){
        switch($this->type){
        case 1:
            return '日期条件';
        case 2:
            return '数量条件';
        case 3:
            return '范围条件';
        case 4:
            return '自定义条件';
        case 5:
            return '软标准';
        default:
            return '无效条件';
        }
    }
    public function task()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_id');
    }
}
