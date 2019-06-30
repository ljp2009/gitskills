<?php

namespace App\Models;
use Auth;
use Illuminate\Database\Eloquent\Model;

class HallObjModel extends Model
{
    
    public function scopeUserIs($query,$userId)
    {
    	return $query->where('user_id',$userId);
    }
    public function scopeCurrentUser($query)
    {
    	if(Auth::check())
    	{
    		return $query->where('user_id','in',[0, Auth::user()->id]);
		}
		else
		{
    		return $query->where('user_id','=','0');
		}
    }

}
