<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpAttr extends Model
{
   	protected $table = 't_ip_attr';
  	protected $guarded = ['id'];
  	public function attrSet()
  	{
  		return $this->hasOne('App\Models\SysAttr','code','code');
  	}
  	public function getDisplayAttribute()
  	{
      if($this->value == '') return '';
  		return str_replace('{value}',$this->value,$this->attrSet->display);	
  	}
}
