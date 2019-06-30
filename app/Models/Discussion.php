<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Discussion extends Model
{
	 protected $table = 't_discussion';
   	 protected $guarded = ['id'];
     public function reference(){
        return $this->hasOne('App\Discussion', 'id', 'reference_id');
     }
     public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
     }
     public function scopeResource($query,$res)
     {
        return $query->where('resource',$res);
     }
     public function scopeBelongId($query,$id)
     {
        return $query->where('resource_id',$id);
     }
     public function getIsLikeAttribute(){
         if(!Auth::check())return false;
         $like = LikeModel::where('resource_id', $this->id)
                ->where('resource','discussion')
                ->where('user_id', Auth::user()->id)->first();
        if (!is_null($like) && $like->like_sum_id > 0) {
            # code...
            return true;
        }
         return false;
     }
     public static function countDiscuss($resource, $resourceId){
         return Discussion::resource($resource)->belongId($resourceId)->count();
     }
}
