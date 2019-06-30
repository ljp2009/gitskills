<?php
/*
 * 短信发送类，所有邮件发送的出口
* */
namespace App\Common;

use AliSMS;
use iscms\Alisms\SendsmsPusher as Sms;
use Config;
class SMSHandler{
    public static function SendValidateSMS($user){
        $userName = $user->display_name;
        $to = $user->mobile;
        if(empty($to)){
            return false;
        }else{
            $code = self::getValidateCode($user);
            $aliSMS = new Sms;
            $aliSMS->send($user->mobile,"有妹社区","{'code':$code,'product':'有妹社区'}",'SMS_3090063');
            return true;
        }
    }
    public static function SendResetPwdSMS($user){
    }
    private static function getValidateCode($user){
        return $user->id.'000';
    }
}

?>
