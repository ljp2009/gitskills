<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTimeline extends Model
{
    const TTTYPE_MILESTONE = 'milestone';
    const TTTYPE_STATUS    = 'state';
    const TTTYPE_SYSTEM    = 'system';

    const TTSTATUS_WAIT   = 'wait';
    const TTSTATUS_FINISH = 'finish';
    const TTSTATUS_DELAY  = 'delay';
    const TTSTATUS_FAILED = 'failed';

    protected $table   = 't_task_timeline';
    protected $guarded = ['id'];
    public function getFinishDateValueAttribute()
    {
        return date('Y-m-d', $this->finish_date);
    }
    public function getTextAttribute()
    {
        return $this->user->display_name . $this->result;
    }
    public function scopeAllMileStone($query, $task_id)
    {
        return $query->where('task_id', $task_id)->where('type', self::TTTYPE_MILESTONE)->orderBy('expect_date');
    }
    public function task()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function getStatusClassAttribute()
    {
        $cls = 'am-badge-default';
        switch($this->status){
            case self::TTSTATUS_WAIT:
                $cls = 'am-badge-secondary';
                break;
            case self::TTSTATUS_FINISH:
                $cls = 'am-badge-success';
                break;
            case self::TTSTATUS_DELAY:
                $cls = 'am-badge-warning';
                break;
            case self::TTSTATUS_FAILED:
                $cls = 'am-badge-danger';
                break;
        }
        return $cls;
    }
}
