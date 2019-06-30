<?php
/*
 * 资源地图类
* */
namespace App\Common;
use App\Models as MD;
use Auth;
class OwnerHandler{
    public static function checkById($resource, $resourceId=0){
        if(!Auth::check()){ //未登录
            return false;
        }
        if(self::checkAdmin(Auth::user())){ //管理员
            return true;
        }
        
        $obj = self::getObj($resource, $resourceId);
        return self::checkByObj($resource, $resourceObj);
    }
    public static function checkByObj($resource, $resourceObj){
        if(!Auth::check()){ //未登录
            return false;
        }
        if(self::checkAdmin(Auth::user())){ //管理员
            return true;
        }
        if(Auth::id() == $resourceObj->user_id){ //内容创建者
            return true;
        }else{
            return false;
        }
    }
    private static function checkAdmin($user){
       return $user->role == 'admin';
    }
    private static function getObj($resource, $resourceId){
        switch($resource){
        case 'ip':
            return MD\Ip::find($resourceId);
        case 'ip_scene':
            return MD\IpScene::find($resourceId);
        case 'ip_dialogue':
            return MD\IpDialogue::find($resourceId);
        case 'ip_role':
            return MD\IpRole::find($resourceId);
        case 'dimension':
            return MD\Dimension::find($resourceId);
        case 'dimension_publish':
            return MD\DimensionPublish::find($resourceId);
        case 'discussion':
            return MD\Discussion::find($resourceId);
        default:
            return null;
        }
    }
}
?>
