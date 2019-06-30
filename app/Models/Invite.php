<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 邀请model
 * @author admin
 *
 */
class Invite extends Model
{
    protected $table = 't_invite';

  	protected $guarded = ['id'];
  	//关联
  	public function getUserInfo()
  	{
  		return $this->hasOne('App\Models\User','id','user_id');
  	}
    public function fromUser(){
    	return $this->hasOne('App\Models\User','id','user_id');
    }

}
