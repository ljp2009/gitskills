<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmeiiiRecommendBatch extends Model
{
    protected $table     = 't_umeiii_recommend_batch';
    protected $guarded   = ['id'];
    public function recommends(){
        return $this->hasMany('App\Models\UmeiiiRecommend','batch_id','id');
    }
}
