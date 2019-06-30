<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Models\LikeModel;
use Auth;
class TaskDelivery extends Model
{
    protected $table = 't_task_delivery';
    protected $guarded = ['id'];
    public function getImageAttribute($value){
        return Image::makeImages($value);
    }
    public function getReviewImgAttribute(){
        if(count($this->image) > 0){
            return $this->image[0];
        }
        return null;
    }
    public function getAttachmentsAttribute(){
        $strArr = explode(';', $this->attachment);
        $res = [];
        foreach($strArr as $str){
            $arr = explode(':', $str);
            if(count($arr) != 3){
                continue;
            }
            $size = floatval($arr[1]);
            $lv = 'byte';
            if($size > 1024.00){
                $size = $size / 1024.00;
                $lv = 'kb';
            }
            if($size > 1024.00){
                $size = $size / 1024.00;
                $lv = 'mb';
            }
            $res[$arr[0]] = [
                'url'=>'http://static.umeiii.com/delivery/'.$arr[0],
                'name'=>$arr[2],
                'size' =>$arr[1],
                'show' =>$arr[2].' ('.number_format($size,2).$lv.')'];
        }
        return $res;
    }
    public function getIsLockedAttribute(){
        if(Auth::id() == $this->user_id){
            $lockTime = strtotime('+1 days', strtotime($this->created_at));
            $now = strtotime('now');
            return ($now>$lockTime);
        }
        return true;
    }
    public function checkLike(){
        $isLike = LikeModel::CheckLike('task_delivery',$this->id);
        return $isLike;
    } 
    public function user()
    {
    	return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function getDetailUrlAttribute()
    {
    	return '/task/delivery/'.$this->id;
    }
    public function task(){
    	return $this->hasOne('App\Models\Task', 'id', 'task_id');
    }
}
