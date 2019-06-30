<?php
/*
 * 邮件发送类，所有邮件发送的出口
* */
namespace App\Common;

use Mail;
use Config;
class EmailHandler{
    public static function SendValidateEmail($code, $email){
        $view = self::getEmailView('validate');
        $params = [];
        $params['name'] = '新朋友';
        $params['token'] = $code;
        $params['host'] = Config::get('app.urlhost');
        $subject = '【有妹社区】验证邮箱';
        $userName ='新朋友';
        $to = $email;
        if(empty($to)){
            return false;
        }else{
            self::send($view, $params, $subject, $to, $userName);
            return true;
        }
    }
    public static function SendResetPwdEmail($user){
        $view = self::getEmailView('resetpwd');
        $params = [];
        $params['name'] = $user->display_name;
        $params['token'] = $this->getEmailValidateToken($user);
        $subject = '【有妹社区】重置用户密码。';
        $userName = $user->display_name;
        $to = $user->email;
        if(empty($email)){
            return false;
        }else{
            self::send($view, $params, $subject, $to, $userName);
            return true;
        }
    }
    private static function send($view, $params, $subject, $to, $userName){
        Mail::send($view, $params, function($message) use($to, $userName, $subject) {
            $message->to($to, $userName)->subject($subject);
        });
    }
    private static function getEmailView($viewName){
        return 'emails.'.$viewName;
    }
}

?>
