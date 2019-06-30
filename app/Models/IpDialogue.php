<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Verifiedable;
class IpDialogue extends Model
{
    use SoftDeletes;
    use Verifiedable;

	protected $table = 't_ip_dialogue';
  	protected $guarded = ['id'];
  	//protected $dates = ['deleted_at'];

	public function user(){
		return $this->hasOne('App\Models\User', 'id', 'user_id');
	}
    public function ip(){
        return $this->hasOne('App\Models\Ip', 'id', 'ip_id');
    }
    public function getTextPartAttribute(){
        $c = count($this->text);
        if($c > 2){
            $newArr = [];
            for($i=0;$i<($c-1);$i++){
                array_push($newArr, $this->text[$i]);
            }
            return implode('——',$newArr);
        }else{
            return $this->text[0];
        }
    }
    public function getRolePartAttribute(){
        $c = count($this->text);
        $role = $this->text[$c-1];
        return empty($role)?'':$role;
    }
    public function setTextAttribute($valueArr){
       $this->attributes['text'] = implode('——',$valueArr);
    }
    public function getTextAttribute($value){
        $arr = explode('——',$value);
        if(count($arr) == 1) $arr[1] = '';
        return $arr;
    }
	public function getDetailUrlAttribute()
    {
        return '/ipdialogue/'.$this->id;
    }
}
