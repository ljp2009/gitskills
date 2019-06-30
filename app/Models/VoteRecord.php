<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteRecord extends Model
{
    protected $table = 't_vote_record';
    protected $guarded = ['id'];

    public function vote(){
        return $this->hasOne('App\Models\Vote','id','vote_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
    
}
