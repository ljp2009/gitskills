<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMilestone extends Model
{
    //
    protected $table   = 't_task_milestone';
    protected $guarded = ['id'];
    private $isActive = false;
    private $allowSignIn = false;
    public function getIsDelayAttribute(){
        return $this->status != 'finish' && $this->date<date('Y-m-d');
    }

    public function getStatusNameAttribute(){
        $txt = '';
        switch($this->status){
        case 'wait':
            if($this->isActive){
                $txt = '等待双方确认';
            }else{
                $txt = '未开始';
            }
            break;
        case 'A':
            $txt = '等待乙方确认';
            break;
        case 'B':
            $txt = '等待甲方确认';
            break;
        case 'finish':
            $txt = '里程碑已完成';
            break;
        }
        return $txt;
    }
    public function getIsActiveAttribute(){
        return $this->isActive;
    }
    public function setIsActiveAttribute($value){
        $this->isActive = $value;
    }
    public function getAllowSignInAttribute(){
        return $this->allowSignIn;
    }
    public function setAllowSignInAttribute($value){
        $this->allowSignIn = $value;
    }
    public function task(){
        return $this->belongsTo('App\Models\Task', 'task_id');
    }

}
