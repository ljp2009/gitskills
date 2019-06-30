<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\Enums;
use App\Models\User;

class UserInfo extends Model
{
    protected $table     = 't_user_info';
    protected $guarded   = ['id'];

    public function getSexAttribute($value){
        return Enums::createByCode('sex', $value);
    }
    public function getBirthdayAttribute($value){ 
        return $value;
    }
  	public function getAgeAttribute() {
        if(is_null($this->birthday)){
            return -1;
        }
        $birthday = $this->birthday;
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
  	}
    public function getEducationAttribute($value){
        return Enums::createByCode('education', $value);
    }
    public function getJobAttribute($value){ 
        return Enums::createByCode('job', $value);
    }
    public function getMarriageAttribute($value){
        return Enums::createByCode('marriage', $value);
    }
    public function user() {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
