<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysTag extends Model
{
    
    protected $table = 'sys_tag';
     
  	protected $guarded = ['id'];

  	public function scopeIpTags($query)
  	{
  		return $query->where('depend', 'ip');
  	}
}
