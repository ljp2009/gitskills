<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysAttr extends Model
{
    protected $table = 'sys_attr';
    
  	protected $guarded = ['id'];

  	public function scopeIpAttrs($query)
  	{
  		return $query;
  	}
  	public function scopeUserAttrs($query)
  	{
  		return $query->where('depend', 'user');
  	}
}
