<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $table = 't_user_badge';
    
  	protected $guarded = ['id'];
  	
  	public function getBadge()
  	{
  		return $this->hasOne('App\Models\SysBadge','id','badge_id');
  	}
  	
  	public function getDisplay()
  	{
  		$this->getBadge();
  		return $this->img;
  	}
}
