<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskConfirmRequest extends Model
{
    protected $table   = 't_task_confirm_request';
    protected $guarded = ['id'];
}
