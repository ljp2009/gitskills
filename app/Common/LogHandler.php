<?php
/**
 * 日志管理通用模块
 * 负责记录系统日志的访问记录
 * @author xiaocui
 * @date 2016-05-16
 */
namespace App\Common;

use App\Models\User;
use Auth,DB;
use App\Models\VisitLog;
use App\Models\SystemLog;
use App\Models\SysOuterLog;
use Log, Config;
class LogHandler
{
	public static function recordVisitLog($resource, $resourceId=''){
        $userId = self::getUserId();
        if($userId == 0){
            return;
        }
        $visitLog = VisitLog::where('user_id', $userId)
            ->where('resource', $resource)
            ->where('resource_id', $resourceId)
            ->first();
        if(is_null($visitLog)){
            $visitLog = new VisitLog;
            $visitLog->user_id = self::getUserId();
            $visitLog->action  = self::getAction();
            $visitLog->resource_id = empty($resourceId)?0:$resourceId;
            $visitLog->resource    = $resource;
        }
        $visitLog->mobile  = self::getAgent('os');
        $visitLog->page    = self::getUrl();
        $visitLog->ip      = self::getIpAddress();
		$visitLog->save();
	}
    public static function recordSystemLog(){
        $url = self::getUrl();
        $domain = Config::get('app.domain');
        $res = stripos($url, $domain);
        $systemLog = null;
        if($res === false){//非网站域名日志
            $systemLog = new SysOuterLog;
        }else{//网站域名日志
            $systemLog = new SystemLog;
        }
        $systemLog->ip_address = self::getIpAddress();
		$systemLog->page = self::getUrl();
        $systemLog->source = self::getAgent('os');
        $systemLog->user_id = self::getUserId();
        $systemLog->method = self::getAction();
        try{
            $systemLog->save();
        }catch(Exception $ex){
            Log::warning('Write Log Error', ['url'=>$systemLog->page]);
        }
    }
    public static function recordInnerLog(){
        $systemLog = new SystemLog;
        $systemLog->ip_address = self::getIpAddress();
		$systemLog->page = self::getUrl();
        $systemLog->source = self::getAgent('os');
        $systemLog->user_id = self::getUserId();
        $systemLog->method = self::getAction();
        try{
            $systemLog->save();
        }catch(Exception $ex){
            Log::warning('Write Log Error', ['url'=>$systemLog->page]);
        }
    }
    public static function recordOuterLog(){
        $systemLog = new SysOuterLog;
        $systemLog->ip_address = self::getIpAddress();
		$systemLog->page = self::getUrl();
        $systemLog->source = self::getAgent('os');
        $systemLog->user_id = self::getUserId();
        $systemLog->method = self::getAction();
        try{
            $systemLog->save();
        }catch(Exception $ex){
            Log::warning('Write Log Error', ['url'=>$systemLog->page]);
        }
    }
    public static function getUrl(){
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    public static function getUserId(){
    	$user_id = Auth::check() ? Auth::user()->id : 0;
        return $user_id;
    }
    public static function getAction(){
        return $_SERVER['REQUEST_METHOD'];
    }
    public static function getIpAddress() {
    	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
    /**
     * 获取代理信息
     * @type null:getAll
     * platform  :* 0 Unknow * 1 mobile * 2 pc * 99 other
     * os        :* 0 Unknow * 1 android * 2 iphone * 99 other
     * browser   :* 0 Unknow * 1 weixin * 2 uc * 3 qq * 4 baidu * 5 weibo * 6 chrome * 7 firefox * 8 360 * 9 open * 99 other
     * @return Array
     */
    public static function getAgent($type=null)
    {
    	$result = array('platform' => 0, 'os' => 0, 'browser' => 0);
    	$agent = $_SERVER['HTTP_USER_AGENT'];
    	//platform
    	if (stripos($agent, 'Mobile') !== false) {
    		$result['platform'] = 1;
    	} else {
    		$result['platform'] = 2;
    	}
    	//os
    	if (stripos($agent, 'Android') !== false) {
    		$result['os'] = 1;
    	} elseif (stripos($agent, '(iPhone') !== false) {
    		$result['os'] = 2;
    	} else {
    		$result['os'] = 99;
    	}
    	//browser
    	if (stripos($agent, 'MicroMessenger') !== false) {
    		$result['browser'] = 1;
    	} else {
    		$result['browser'] = 99;
    	}
        if(is_null($type)){
            return $result;
        }else{
            return $result[$type];
        }
    }
}

