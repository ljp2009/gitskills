<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttr extends Model
{
    protected $table = 't_user_attr';
    
  	protected $guarded = ['id'];
  	
  	
  	public function getDisplay()
  	{
  		return $this->attr_value;
  	}
  	public function getAgeAttribute()
  	{
  		if(!empty($this->attr_value)){
  			$birthday = $this->attr_value;
  			$birthdayArr = explode('-',$birthday);
  			$nowAge = date('Y') - $birthdayArr[0];
  			if(date('m') < $birthdayArr[1]){
  				$nowAge = $nowAge - 1;
  			}else if(date('m') == $birthdayArr[1]){
  				if(date('d') < $birthdayArr[2]){
  					$nowAge = $nowAge - 1;
  				}
  			}
  			return $nowAge;
  		}else{
  			return '';
  		}
  	}
}
