<?php
/*
 * 验证码通用模块
* */
namespace App\Common;

use App\Models\ValidateCode;
use Config;
class VCodeHandler{
    /* * *
     * 检查验证码类型
     * */
    public static function checkType($type){
        return in_array($type, ['mobile', 'email']);
    }
    /* * *
     * 检查Func类型
     * */
    public static function checkFunc($func){
        return in_array($func, ['regist', 'reset', 'bind']);
    }
    /* * *
     * 生成手机验证码
     * */
    public static function makeMobileCode($target, $func){
        $code = sprintf("%'.06d", mt_rand(0,999999));
        $expireTimestamp = strtotime("+60 seconds");
        $expireTime = date('Y-m-d H:i:s',$expireTimestamp );
        $typeCode = 0;
        $funcCode = self::getFuncCode($func);
        $res = self::makeCode($typeCode, $target, $code, $funcCode, $expireTime);
        return $res;
    }
    /* * *
     * 生成邮件验证码
     * */
    public static function makeEmailCode($target, $func){
        $code = sprintf("%'.06d", mt_rand(0,999999));
        $expireTimestamp = strtotime( "+10 minutes");
        $expireTime = date('Y-m-d H:i:s',$expireTimestamp );
        $typeCode = 1;
        $funcCode = self::getFuncCode($func);
        $res = self::makeCode($typeCode, $target, $code, $funcCode, $expireTime);
        return $res;
    }
    private static function getFuncCode($func){
        $map = ['regist'=>0, 'reset'=>1, 'bind'=>2];
        if(array_key_exists($func, $map)){
            return $map[$func];
        }
        return -1;
    }
    private static function getTypeCode($type){
        $map = ['mobile'=>0, 'email'=>1];
        if(array_key_exists($type, $map)){
            return $map[$type];
        }
        return -1;
    }

    private static function makeCode($type, $target, $code, $funcCode, $expireTime){
        if($funcCode < 0) {
            return ['res'=>false, 'err'=>2];
        }
        $validateCode = ValidateCode::where('type',$type)
            ->where('target', $target)
            ->where('func', $funcCode)
            ->first();
 
        //无目标数据
        if(is_null($validateCode)){
            $validateCode = new ValidateCode;        
            $validateCode->code = $code;
            $validateCode->target = $target;
            $validateCode->type = $type;
            $validateCode->func = $funcCode;
            $validateCode->expire_time = $expireTime;
            $validateCode->save();
            return ['res'=>true, 'code'=>$code];
        }
        //验证重复申请的时间间隔
        //$repeatTimestamp = strtotime("-60 seconds");
        //if($validateCode->updated_at > date('Y-m-d H:i:s', $repeatTimestamp)){
            //距离上次申请未满一分钟。防止反复刷验证码的保护措施。
        //    return ['res'=>false, 'err'=>1];
        //}else{
        //}
        $validateCode->code = $code;
        $validateCode->expire_time = $expireTime;
        $validateCode->save();
        return ['res'=>true, 'code'=>$code];
    }

    public static function checkCode($type, $target, $code, $func){
        $typeCode = self::getTypeCode($type);
        $funcCode = self::getFuncCode($func);
        $vc = ValidateCode::where('type',$typeCode)
            ->where('target', $target)
            ->where('func', $funcCode)
            ->where('code', $code)->count();        
        return $vc>0;
    }
}
