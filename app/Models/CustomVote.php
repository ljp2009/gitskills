<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CustomVote extends Model
{
    //
    protected $table     = 't_custom_vote';
    protected $guarded   = ['id'];
    public function targetObj(){
        return $this->hasOne('App\Models\DimensionPublish', 'id', 'resource_id');
    }
}
