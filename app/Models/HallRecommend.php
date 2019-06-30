<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallRecommend extends HallObjModel
{
    protected $table     = 't_hall_ip';
    protected $guarded   = ['id'];
    public function Ip()
    {
        return $this->hasOne('App\Models\Ip','ip_id','id');
    }
}
