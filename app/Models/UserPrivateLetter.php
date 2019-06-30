<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils;
use Auth;
class UserPrivateLetter extends Model
{
    protected $table = 't_user_private_letter';

  	protected $guarded = ['id'];
  	//关联
  	public function getUserInfo()
  	{
  		return $this->hasOne('App\Models\User','id','user_id');
  	}
    public function fromUser(){
    	return $this->hasOne('App\Models\User','id','send_id');
    }
    public function getFromUserAttribute($value){
        if(is_null($value)){
           $u = new User();
           $u->display_name = '系统';
           return $u;
        }
        return $value;
    }
    public function toUser(){
  		return $this->hasOne('App\Models\User','id','user_id');
    }
    public function getDirectionAttribute(){
        if(!Auth::check()) return 'error';
        $user = Auth::user();
        if($user->id == $this->user_id) return 'from';
        if($user->id == $this->send_id) return 'to';
        return 'error';

    }
    public function getFormatMsgAttribute(){
        return CommonUtils::readPrivateLetter($this->msg,$this->type,$this->variable);
    }
}
