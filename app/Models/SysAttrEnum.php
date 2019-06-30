<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysAttrEnum extends Model
{
    protected $table = 'sys_attr_enum';
    
  	protected $guarded = ['id'];
  	
  	public function getColumnsAttribute(){
  		return $this->hasOne('App\Models\SysAttr','code','column');
  	}
}
