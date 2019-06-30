<?php
/*
 * 验证码通用模块
* */
namespace App\Common;

use App\Models\Ip;
use App\Models\IpScene;
use App\Models\IpDialogue;
use App\Models\IpRole;
use App\Models\DimensionPublish;
use App\Models\UserProduction;
use App\Models\Discussion;
use App\Models\UserPrivateLetter;
use Config;
class Notifaction{
    const LIKE       = 0;
    const DISCUSSION = 1;
    const INVITE     = 2;

    /* * *
     * 通知发送通用类
     * */
    public static function Notice($type, $user, $resource, $resourceId){
        $resourceInfo = self::getResourceInfo($resource, $resourceId);
        if(is_null($resourceInfo)){
            return; //无法确定来源的通知会被忽略
        }
        $message      = self::getMessage($resourceInfo, $type, $user);

		$letter = UserPrivateLetter::create([
				'user_id' => $resourceInfo['user'],
				'send_id' => 0,
				'msg'     => $message,
				'status'  => 'N'
				]);
    }
    private static function getResourceInfo($resource, $resourceId){
        $res = [];
        switch($resource){
        case "ip":
            $ip = Ip::findOrFail($resourceId);
            $res = [
                'name' => '您发布的《'.$ip->name.'》。',
                'user' => $ip->user_id,
            ];
            break;
        case "ip_scene":
            $scene = IpScene::findOrFail($resourceId);
            $res = [
                'name' => '您在《'.$scene->ip->name.'》发布的场景。',
                'user' => $scene->user_id,
            ];
            break;
        case "ip_dialogue":
            $dialogue = IpDialogue::findOrFail($resourceId);
            $res = [
                'name' => '您在《'.$dialogue->ip->name.'》发布的台词。',
                'user' => $dialogue->user_id,
            ];
            break;
        case "ip_role":
            $role = IpRole::findOrFail($resourceId);
            $res = [
                'name' => '您在《'.$role->ip->name.'》发布的角色“'.$role->name.'”。',
                'user' => $role->user_id,
            ];
            break;
        case "user_production":
            $production = UserProduction::findOrFail($resourceId);
            $res = [
                'name' => '您发布的《'.$production->name.'》。',
                'user' => $production->user_id,
            ];
            break;
        case "discussion":
            $discussion = Discussion::findOrFail($resourceId);
            $res = [
                'name' => '您发布的评论“'. mb_substr($discussion->text, 0, 4, 'utf-8').'”',
                'user' => $discussion->user_id,
            ];
            break;
        case "dimension_publish":
            $dimPub = DimensionPublish::findOrFail($resourceId);
            $res = [
                'name' => '您次元“'.$dimPub->dimension->name.'”发布的帖子。',
                'user' => $dimPub->user_id,
            ];
            break;
        default:
            $res = null;
            break;
        }
        return $res;
    }
    private static function getMessage($resourceInfo, $type, $user){
        switch($type){
        case self::LIKE:
            return $user->display_name. '喜欢了'. $resourceInfo['name'];
        case self::DISCUSSION:
            return $user->display_name. '评论了'. $resourceInfo['name'];
        }
        return '';
    }
}
