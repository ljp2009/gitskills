<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSum extends Model
{
    protected $table = 't_user_sum';
    
  	protected $guarded = ['id'];
  	
  	public function getDisplay()
  	{
  		return $this->value;
  	}
    public function user(){
       return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
