<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\TaskPartnerStatus;
class TaskPartner extends Model
{
    const PARTERSTATUS_REQUEST = 1;
    const PARTERSTATUS_JOININ  = 2;
    const PARTERSTATUS_PARTNER  = 3;
    const PARTERSTATUS_REJECT  = 0;

    protected $table   = 't_task_parter';
    protected $guarded = ['id'];
    public function getIsWinnerAttribute()
    {
        return $this->status == self::PATRSTATUS_PARTNER;
    }
    public function getStatusNameAttribute(){
        switch($this->status){
        case self::PARTERSTATUS_REQUEST:
            return '申请合作';
        case self::PARTERSTATUS_JOININ:
            return '等待确认';
        case self::PARTERSTATUS_PARTNER:
            return '合作伙伴';
        case self::PARTERSTATUS_REJECT:
            return '已经拒绝';
        }
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task',  'task_id');
    }
}
