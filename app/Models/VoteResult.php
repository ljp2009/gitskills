<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
class VoteResult extends Model
{
    protected $table = 't_vote_result';
    protected $guarded = ['id'];
    
    public function delivery()
    {
    	return $this->hasOne('App\Models\TaskDelivery','id','delivery_id');
    }
    
}
