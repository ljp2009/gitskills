<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
class LikeSumModel extends Model
{
    protected $table = "t_like_sum";
    protected $fillable = ['resource', 'resource_id'];
    public static function countLike2($resource, $resourceId){
        $sum = LikeSumModel::where('resource',$resource)
            ->where('resource_id', $resourceId)->first();
        if(empty($sum)) return 0;
        return $sum->like_sum;
    }
    public static function countLike($resource, $resourceId){
        if($resource == 'role') $resource = 'ip_role';
        $obj = DB::table('t_'.$resource)->select('like_sum')->where('id', $resourceId)->first();
        return $obj->like_sum;
    }
}

?>
