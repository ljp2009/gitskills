<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCancelRequest extends Model
{
    const TYPE_A = 0;//甲方发起
    const TYPE_B = 1;//乙方发起
    const TYPE_SYS = 2;//系统发起(暂时不支持)
     
    const STATUS_CANCEL = 0;//放弃取消
    const STATUS_REQUEST = 1;//发起取消
    const STATUS_CONFIRM = 2;//确认取消
    protected $table     = 't_task_cancel_request';
    protected $guarded   = ['id'];
     
}
