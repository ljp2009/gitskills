<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpSum extends Model
{
    protected $table = 't_ip_sum';
    
  	protected $guarded = ['id'];
  	
  	public function attrSet()
  	{
  		$this->hasOne('App\Models\SysAttr','code','code');
  	}
}
