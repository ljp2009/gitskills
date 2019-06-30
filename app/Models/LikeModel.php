<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
class LikeModel extends Model
{
	protected $table = 't_like';
	protected $guarded = ['id'];
	public function avatarImage()
	{
		return Image::getImagesPath('user','avatar',$this->id);
	}
	public function getAvatarAttribute($value)
	{
		return CU::getImagePath('user','avatar',$value);
	}
	public function user(){
		return $this->hasOne('App\Models\User', 'id', 'user_id');
	}
	public function ipEvaluation(){
		return $this->hasMany('App\Models\IpEvaluate', 'ip_id', 'resource_id');
	}
	public function ipUserLikeInto(){
		return $this->hasOne('App\Models\IpIntro', 'ip_id', 'resource_id');
	}
	public function getUserInfo()
	{
		return $this->hasOne('App\Models\User','id','user_id');
	}
	public function ip()
	{
		return $this->hasOne('App\Models\Ip','id','resource_id');
	}
    public static function CheckLike($resource, $resourceId){
        if(!Auth::check())return false;
        $like = LikeModel::where('resource_id', $resourceId)
                ->where('resource', $resource)
                ->where('like_sum_id', 1)
                ->where('user_id', Auth::id())
                ->first();
        return !is_null($like);
    }
}
