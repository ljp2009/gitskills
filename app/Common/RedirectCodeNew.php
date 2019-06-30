<?php
/*
 * 登录后的重定向规则，主要用来处理不同渠道来源的未登录请求，在登陆后的重定向问题
 * 重定向code的规则如下：
 *  mode.type.resource.resource_id
* */
namespace App\Common;

use Config;
class RedirectCodeNew{

    const DEFCODE    = 'A0000000000000000';
    const WECHATCODE = 'A0001000000000000';
    const QQCODE     = 'A0002000000000000';
    const ADMINCODE  = 'A0000000100000000';

    const MODE_LOGIN   = 'A';
    const MODE_REGIEST = 'B';
    const MODE_GOTO    = 'G';
    const MODE_ENCRYPT = 'E';
    
    const TYPE_WEB    = '0000';
    const TYPE_WECHAT = '0001';
    const TYPE_QQ     = '0002';

    const RES_INDEX          = '0000';
    const RES_ADMIN          = '0001';
    const RES_IP             = '1000';
    const RES_IPSCENE        = '1100';
    const RES_IPDIALOGUE     = '1200';
    const RES_IPROLE         = '1300';
    const RES_USERPRODUCTION = '2000';
    const RES_DIMENSION      = '3000';
    const RES_DIMPUBLISH     = '3100';
    const RES_TASK           = '4000';
    const RES_ACTIVITY       = '5000';
    const RES_SPECIAL        = '6000';
    const RES_CUSTOM         = '9000';

    private $settings = [
        'code'        => '',
        'mode'        => '',
        'type'        => '',
        'resource'    => '',
        'resource_id' => 0,
        'token'       => ''];

    private $token = '';
    public function __get($paramName){
        if(array_key_exists($paramName, $this->settings)){
            return $this->settings[$paramName];
        }
        return null;
    }
    public function __set($paramName, $value){
        if(array_key_exists($paramName, $this->settings)){
            $this->settings[$paramName] = $value;
        }
    }
    public function __construct($code = self::DEFCODE){
        $this->setCode($code);
    }

    public function generateToken(){
        $token = '';
        $randCode = md5(uniqid(rand(10000,99999), TRUE));
        $this->settings['token'] = $randCode;
    }
    public function setCode($code){
        $this->settings['code']        = $code;
        $this->settings['mode']        = self::getRegistStatus($code);
        $this->settings['type']        = self::getType($code);
        $this->settings['resource']    = self::getResource($code);
        $this->settings['resource_id'] = self::getResourceId($code);
    }
    public function getCode(){
        $code = $this->mode.$this->type.$this->resource.sprintf("%08d", $this->resource_id);
        $this->settings['code'] = $code;
        return $code;
    }
    public function getToken(){
        return $this->settings['token'];
    }
    public function getUrl(){
        if(!in_array($this->resource, [self::RES_INDEX, self::RES_CUSTOM])
            && $this->resource_id > 0) {
            //跳转到某个具体页
            $url = self::getDetailUrl($this->resource);
            return str_replace('{id}', $this->resource_id, $url);
        }
        elseif($this->resource == self::RES_CUSTOM){
            //跳转到自定义页面
            $url = self::getCustomUrl($this->resource_id);
            return $url;
        }
        else{
            //跳转到列表页面
            $url = self::getListUrl($this->resource);
            return $url;
        }
    }
    public function getDebug(){
        $debug  = '';
        $debug .= $this->mode;
        $debug .= '-';
        $debug .= $this->type;
        $debug .= '-';
        $debug .= $this->resource;
        $debug .= '-';
        $debug .= $this->resource_id;
        return $debug;
    }
    //----------private this functions-------------------------
    private function 
    //----------private static functions-----------------------
    private static function getListUrl($code){
        switch($code){
        case self::RES_INDEX:
            return '/reshall';
        case self::RES_ADMIN:
            return '/admin/ip/list';
        case self::RES_IP:
            return '/reshall';
        case self::RES_IPSCENE:
            return '/reshall';
        case self::RES_IPDIALOGUE:
            return '/reshall';
        case self::RES_IPROLE:
            return '/reshall';
        case self::RES_USERPRODUCTION:
            return '/reshall';
        case self::RES_DIMENSION:
            return '/reshall';
        case self::RES_DIMPUBLISH:
            return '/reshall';
        case self::RES_TASK:
            return '/reshall';
        case self::RES_ACTIVITY:
            return '/reshall';
        case self::RES_SPECIAL:
            return '/reshall';
        case self::RES_CUSTOM:
            return '/reshall';
        }
        return '/';
    }
    private static function getDetailUrl($code){
        switch($code){
        case self::RES_INDEX:
            return '/reshall';
        case self::RES_ADMIN:
            return '/admin/ip/list';
        case self::RES_IP:
            return '/ip/{id}';
        case self::RES_IPSCENE:
            return '/ipscene/{id}';
        case self::RES_IPDIALOGUE:
            return '/ipdialogue/{id}';
        case self::RES_IPROLE:
            return '/roles/{id}';
        case self::RES_USERPRODUCTION:
            return '/user/product/{id}';
        case self::RES_DIMENSION:
            return '/dimpub/list/diminfo/0/{id}';
        case self::RES_DIMPUBLISH:
            return '/dimpub/{id}';
        case self::RES_TASK:
            return '/reshall';
        case self::RES_SPECIAL:
            return '/special/detail-{id}/0';
        case self::RES_ACTIVITY:
            return '/activity/list/join/0/{id}';
        case self::RES_CUSTOM:
            return '/reshall';
        }
        return '/';
    }
    private static function getCustomUrl($resourceId){
        if($resourceId == 1){
            return '/custom/ido21/vote';
        }
        return '/reshall';
    }
    public static function getRegistStatus($code){
        $value = substr($code, 0, 1);
        return $value;
    }
    public static function getType($code){
        $value = substr($code, 1, 4);
        return $value; 
    }
    public static function getResource($code){
        $value = substr($code, 5, 4);
        return $value; 
    }
    public static function getResourceId($code){
        $value = substr($code, 9, 8);
        return intval($value); 
    }
    public static function getUserCode($code){

    }
    public static function getToken($code){
        $value = substr($code, 18);
        return $value;
    }
    public static function encrypt($code){
        return 'E'.$code; 
    }
    public static function decrypt($code){
        $value = substr($code, 1);
        return $value; 
    }
}
?>
