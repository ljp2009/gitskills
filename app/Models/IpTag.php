<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpTag extends Model
{
    protected $table = 't_ip_tag';
    
  	protected $guarded = ['id'];

  	public function tagInfo()
  	{
  		return $this->hasOne('App\Models\SysTag','code','tag_id');
  	}
  	public function getDisplayAttribute()
  	{
      if(is_null($this->tagInfo)){
        return '';
      }
  		return $this->tagInfo->name;
  	}
}
