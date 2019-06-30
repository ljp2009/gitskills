<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysNotification extends Model
{
    const TYPE_PUBLIC  = 0; //系统公告
    const TYPE_LIKE    = 1; //like通知
    const TYPE_COMMENT = 2; //评论通知
    const TYPE_INVITE  = 3; //邀请
    const TYPE_ACTION  = 4; //动态
    const TYPE_TASK_REQUEST = 20; //申请加入(参与者)
    const TYPE_TASK_AGREE   = 21; //同意加入(PK)
    const TYPE_TASK_COMMIT  = 22; //确认加入(提醒参与者确认加入）
    const TYPE_TASK_DELIVERY = 24; //参与者同意，进入交付阶段
    const TYPE_TASK_FINISH   = 25; //参与者同意，进入交付阶段
    
    protected $table     = 't_sys_notification';
    protected $guarded   = ['id'];
    public static function getCode($name){
        $code = -1;
        switch($name){
        case 'public':
            $code = self::TYPE_PUBLIC;
            break;
        case 'like':
            $code = self::TYPE_LIKE;
            break;
        case 'comment':
            $code = self::TYPE_COMMENT;
            break;
        case 'invite':
            $code = self::TYPE_INVITE;
            break;
        case 'action':
            $code = self::TYPE_ACTION;
            break;
        case 'task':
            $code = self::TYPE_TASK_REQUEST;
            break;
        }
        return $code;
    }
    public static function getResourceInfo($resource){
        $info = [];
        switch($resource){
            case 'ip':
                $info['objName'] = 'App\Models\Ip';
                $info['nameField'] ='name';
                $info['imgField'] ='cover';
                break;
            case 'user_production':
                $info['objName'] = 'App\Models\UserProduction';
                $info['nameField'] ='name';
                $info['imgField'] ='cover';
                break;
            case 'ip_scene':
                $info['objName'] = 'App\Models\IpScene';
                $info['nameField'] ='text';
                $info['imgField'] ='cover';
                break;
            case 'ip_dialogue':
                $info['objName'] = 'App\Models\IpDialogue';
                $info['nameField'] = 'text';
                $info['imgField'] = null;
                break;
            case 'ip_role':
                $info['objName'] = 'App\Models\IpRole';
                $info['nameField'] ='name';
                $info['imgField'] = 'header';
                break;
            case 'dimension_publish':
                $info['objName'] = 'App\Models\DimensionPublish';
                $info['nameField'] ='text';
                $info['imgField'] = 'cover';
                break;
            case 'task':
                $info['objName'] = 'App\Models\Task';
                $info['nameField'] ='text';
                $info['imgField'] = 'cover';
                break;
        }
        return $info;
    }
    public function resUser(){
        return $this->hasOne('App\Models\User','id','resource_user');
    }
    public function resObj(){
        $objInfo = self::getResourceInfo($this->resource);
        if(count($objInfo)>0){
            return $this->hasOne($objInfo['objName'], 'id', 'resource_id');
        }
        return null;
    }
    public function getMsgAttribute(){
        switch($this->type){
            case self::TYPE_LIKE:
                $msg = '{user}喜欢了你发布的：';
                break;
            case self::TYPE_COMMENT:
                $msg = '{user}评论了你发布的：';
                break;
            case self::TYPE_INVITE:
                $msg = '{user}邀请你参加：';
                break;
        }
        if(isset($msg)){
            $msg = str_replace('{user}', $this->resUser->display_name, $msg);
            return $msg;
        }else{
            return $this->text;
        }
    }
    public function getReferenceAttribute(){
        $resObj = $this->resObj;
        if(is_null($resObj)){
            return null;
        }
        $objInfo = self::getResourceInfo($this->resource);
        if(count($objInfo) == 0){
            return null;
        }
        $nameField = $objInfo['nameField'];
        $imgField = $objInfo['imgField'];
        $info = [];
        $info['name']  = $resObj->$nameField;
        $info['image'] = is_null($imgField)?null:$resObj->$imgField;
        return $info;
    }
    public function task(){
        if($this->resource == 'task'){
            return $this->hasOne('App\Models\Task','id','resource_id');
        }else{
            return null;
        }
    }
}
