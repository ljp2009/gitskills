<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    
    protected $table = 't_user_relation';
    
  	protected $guarded = ['id'];
  	
  	//关联
  	public function getUserInfo()
  	{
  		return $this->hasOne('App\Models\User','id','user_id');
  	}
	//关联关注者
  	public function getFollowInfo(){
  		return $this->hasOne('App\Models\User', 'id', 'follow_id');
  	}
 
}
