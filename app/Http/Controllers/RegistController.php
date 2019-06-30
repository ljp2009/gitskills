<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserDetailStatus;
use App\Models\ValidateCode;
use App\Common\VCodeHandler;
use App\Common\RedirectCode;
use App\Common\Enums;
use iscms\Alisms\SendsmsPusher as Sms;
use Auth, Email, Mail;

class RegistController extends Controller
{

    private $sms = null;
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
        $this->middleware('guest', ['except' =>
            [ 'postSendValidateCode',
              'getLogout']]);
    }
    /*
     * 显示注册页面
     * @return 返回页面
     * */
    public function getIndex(){
        return view('regist.register');
    }
    public function getShow($redirectCode=''){
        return view('regist.register', ['redirectCode'=>$redirectCode]);
    }
    /*
     * 发送验证码
     * @return 发送结果
     * */
    public function postSendCode(Request $request){
        $userName  = $request['userName'];
        $paramType = is_numeric($userName)? 'mobile':'email';
        $paramName = is_numeric($userName)? '手机':'邮箱';

        if($this->checkUserExist($userName, $paramType)){
            return response()->json(['res'=>false, 'info'=>'帐号已经注册过了。']);
        }

        //generate code 
        $code = '000000';
        $makeCodeFunc = camel_case('make_'.$paramType.'_code');
        $res = VCodeHandler::$makeCodeFunc($userName, 'regist');
        if(!$res['res']){
            $res['info'] = '生成验证码失败，请稍后再试。';
            return response()->json($res);
        }
        $code = $res['code'];

        //send code 
        $this->sendCode($code, $userName, $paramType);
        
        //need send function
        
        $request->session()->put('regist_user_name', $userName);
        $request->session()->put('regist_user_type', $paramType);
        $request->session()->put('regist_code', $code);
        if(!$request->session()->has('regist_failed_time')){
            $request->session()->put('regist_validate_times', 0);
        }

        return response()->json(['res'=>true, 'info'=>'验证码已经发送到您的' . $paramName]);
    }

    private function sendCode($code, $target, $type){
        if($type == 'mobile') {
            $mobile = $target;
            $res = $this->sms->send($mobile,'有妹社区','{"code":"'.$code.'","product":"有妹社区"}','SMS_3090063');
        }
        if($type == 'email') {
            $params = [];
            $params['name'] = '有妹用户';
            $params['token'] = $code;
            $userName = '有妹用户';
            $subject = '[有妹社区]验证邮箱';
            $email = $target;
            Mail::send('emails.validate', $params, function($message) use($email, $userName, $subject) {
                $message->to($email, $userName)->subject($subject);
            });
        }
    }

    /*
     * 验证账户状态
     * @return 返回页面
     * */
    public function postValidate(Request $request){
        $maxValidateTimes = 5;
        $failLockTime     = 10;

        $userName      = $request->session()->get('regist_user_name','');
        $userCode      = $request->session()->get('regist_code','');
        $validateTimes = $request->session()->get('regist_validate_times',0);
        $inputUserName = $request['userName'];
        $inputCode     = $request['validateCode'];
        $type = $request->session()->get('regist_user_type', '');
        if($userName == ''){
            return response()->json(['res'=>false, 'info'=>'请先获取验证码。']);
        }
        if($validateTimes >= $maxValidateTimes){
            $failTime = $request->session()->get('regist_failed_time');
            $seconds  = (strtotime(date('Y-m-d h:i:s')) - strtotime($failTime));
            if($seconds < $failLockTime*60){
                $minutes = $seconds / 60;
                $minutes = $failLockTime - (int)$minutes;
                return response()->json(['res'=>false, 'info'=>'多次验证失败，请'.$minutes.'分钟后再试。']);
            }else{
                $request->session()->put('regist_validate_times', 0);
                $request->session()->forget('regist_failed_time');
            }
        }
        if( $userName == $inputUserName
            && $userCode == $inputCode
            && VCodeHandler::checkCode($type, $userName, $inputCode, 'regist')){
            if($this->checkUserExist($userName, $type)){
                return response()->json(['res'=>false, 'info'=>'帐号已经注册过了。']);
            }

            return response()->json(['res'=>true, 'info'=>(string)view('regist.password',['userName'=>$userName])]);

        }else{
            $validateTimes++;
            $request->session()->put('regist_validate_times', $validateTimes);

            if($validateTimes == $maxValidateTimes){
                $request->session()->put('regist_failed_time', date('Y-m-d h:i:s'));
            }
            return response()->json(['res'=>false, 'info'=>'验证码有误，请重试']);
        }
    }

    /*
     * 显示注册页面
     * @return 返回页面
     * */
    public function postRegister(Request $request){
        $userName    = $request->session()->get('regist_user_name', '');
        $type        = $request->session()->get('regist_user_type', '');
        $displayName = $request['displayName'];
        $avatar      = $request['avatar'];
        $pwd         = $request['pwd'];

        $request->session()->put('regist_user_name','');
        $request->session()->put('regist_user_type', '');

        if($this->checkUserExist($userName, $type) || empty($userName) || empty($type)){
            return response()->json(['res'=>false, 'info'=>'帐号已经注册过了。']);
        }

        $user = new User;
        $user->$type        = $userName;
        $user->display_name = $displayName;
        $user->avatar       = $avatar;
        $user->password     = bcrypt($pwd);
        $user->save();

        $userId   = $user->id;
        $userInfo = new UserInfo();
        $userInfo->user_id = $userId;
        $userInfo->sex       = '2000200';
        $userInfo->job       = '2000500';
        $userInfo->education = '2000400';
        $userInfo->save();

        $userId   = $user->id;
        $detailStatus = new UserDetailStatus();
        $detailStatus->user_id = $userId;
        $detailStatus->gold = 2000;
        $detailStatus->receive_gold = 0;
        $detailStatus->pay_all = 0;
        $detailStatus->income_all = 0;
        $detailStatus->save();
        Auth::login($user);
        $arr = ['res'=>true, 'info'=>''];
        $redirectCodeStr = $request['redirectCode'];
        $redirectCode = new RedirectCode($redirectCodeStr);
        $arr['url'] = $redirectCode->getUrl();
        return response()->json($arr);
    }

    private function checkUserExist($userName, $type){
        $ct = User::where($type, $userName)->count();
        return $ct > 0;
    }
}
