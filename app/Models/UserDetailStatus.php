<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetailStatus extends Model
{
    protected $table = 't_user_detail_status';
    
  	protected $guarded = ['id'];
  	
  	public function attrSetBehavior()
  	{
  		return $this->hasOne('App\Models\SysAttrEnum','code','behavior');
  	}
	public function attrSetCharacter()
  	{
  		return  $this->hasOne('App\Models\SysAttrEnum','code','character');
  	}
  	public function attrSetPosition()
  	{
  		return  $this->hasOne('App\Models\SysAttrEnum','code','position');
  	}
}
