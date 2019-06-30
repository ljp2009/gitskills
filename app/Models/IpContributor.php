<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpContributor extends Model
{
    	protected $table = 't_ip_contributor';
  		protected $guarded = ['id'];
  		
  		public function user(){
  			return $this->hasOne('App\Models\User', 'id', 'user_id');
  		}
}
