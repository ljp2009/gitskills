<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetailStatus;
use App\Models\UserInfo;

use App\Models\ValidateCode;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Common\CommonUtils as cu;
use iscms\Alisms\SendsmsPusher as Sms;
use App\Common\VCodeHandler;
use App\Common\WechatHandler;
use App\Common\CreditManager;
use App\Common\RedirectCode;
use App\Common\Enums;
use Input, Redirect, Email, Config;
use Mail;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
     */
    public $redirectTo = '/reshall';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //注入短信发送对象
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
        $this->middleware('guest', ['except' =>
            [ 'postSendValidateCode',
              'getLogout']]);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
        //  'email'    => 'email|max:255|unique:t_user',
        //	'mobile'    => 'required|mobile|max:255|unique:t_user',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /********************
     * 登录验证
     ********************/
    public function getLoginAdmin(){
        return view('auth.login', ['redirectCode'=>RedirectCode::ADMINCODE]);
    }
    public function getLoginNormal($redirectCode = RedirectCode::DEFCODE){
        return view('auth.login', ['redirectCode'=>$redirectCode]);
    }

    public function postLogins(Request $request)
    {
        $userName        = $request['uname'];
        $paramName       = is_numeric($userName)? 'mobile':'email';
        $password        = $request['password'];
        $redirectCodeStr = $request['redirectCode'];
        //检查重复尝试次数
    	$throttles = $this->isUsingThrottlesLoginsTrait();
    	if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->returnInfo(false, '您尝试的过于频繁，请稍后再尝试');
    	}
        $redirectCode = new RedirectCode($redirectCodeStr);

        $validateParams = [
            $paramName => $userName,
            'password' => $password
        ];
        if ($redirectCode->resource == RedirectCode::RES_ADMIN){
            $validateParams['role'] = 'admin';
        }
        if (Auth::attempt($validateParams, $request->has('remember'))) {
            return $this->returnInfo(true, '登录成功', $redirectCode->getUrl(), $redirectCode->getDebug());
        }else{
            if ($throttles) {
                $this->incrementLoginAttempts($request);
            }
            return $this->returnInfo(false, '账号或密码输入错误。');
        }
    }
    private function returnInfo($res, $msg, $url='',$debug=''){
        return response()->json(['res'=>$res, 'info'=>$msg, 'url'=>$url, 'debug'=>$debug]);
    }

    /********************
     * 注册用户
     ********************/
    public function postRegisterUser() {
        $type = Input::get('type');
        $value = Input::get('value');
        $validateCode = Input::get('validate_code');
        $password = Input::get('password');
        $password2 = Input::get('password2');
        //validate type 
        if(!VCodeHandler::checkType($type)){
            //无效的输入
            return back()->withInput()->with('err', '无效的输入。');
        }

        //validate code 
        if(!VCodeHandler::checkCode($type, $value, $validateCode, 'regist')){
            //验证码无效 
            return back()->withInput()->with('err', '验证码错误。');
        }

        //validate user exist 
        $userExist = $this->checkExistUser($type, $value, 'regist');
        if(!$userExist['res']){
            //注册信息已经存在
            return back()->withInput()->with('err', '用户信息已经存在。');
        }

        //validate pwd
        if(strlen($password)<8 || $password != $password2){
            //密码无效
            return back()->withInput()->with('err', '密码无效。');
        }

        //Register User
        $user = new User;
        $user->$type = $value;
        $user->display_name = $value;
        $user->password = bcrypt($password);
        $user->save();

        // 注册成功
        $this->initUser($user);
        Auth::login($user);
        //返回注册成功页面
        return view('auth.regsuccessful', ['user'=>$user]);
    }
    private function initUser($user){
        //credit
        CreditManager::initCredit($user->id);
        //detail_status 
    }

    /********************
     * 重置密码
     ********************/
    public function getResets(){ return view('auth.password');}
    public function postResets() {
        $type = Input::get('type');
        $value = Input::get('value');
        $validateCode = Input::get('validate_code');
        $password = Input::get('password');
        $password2 = Input::get('password2');
        //validate type 
        if(!VCodeHandler::checkType($type)){
            //无效的输入
            return cu::ajaxReturn(-1, '无效的输入。', $param=[]);
        }

        //validate code 
        if(!VCodeHandler::checkCode($type, $value, $validateCode, 'reset')){
            //验证码无效 
            return cu::ajaxReturn(-1, '验证码错误。', $param=[]);
        }

        //validate user exist 
        $userExist = $this->checkExistUser($type, $value, 'reset');
        if(!$userExist['res']){
            //注册信息已经存在
            return cu::ajaxReturn(-1, '用户不存在。', $param=[]);
        }

        //validate pwd
        if(strlen($password)<8 || $password != $password2){
            //密码无效
            return cu::ajaxReturn(-1, '密码无效。', $param=[]);
        }

        //Register User
        $user = $userExist['user'];
        $user->password = bcrypt($password);
        $user->save();
        // 注册成功
        Auth::login($user);
        //返回注册成功页面
        return cu::ajaxReturn(1, '重置密码成功。', $param=[]);
    }
    /********************
     * 重置密码成功画面
     ********************/
    public function resetsSuccess(){
        return view('auth.resetsuccessful', $param=[]);
    }

    /********************
     * 获取验证码
     ********************/
    public function postSendValidateCode(){
        $target = Input::get('value');
        $type = Input::get('type');
        $func = Input::get('func');
        $res = ['res'=>false, 'err'=>0];
        //检查输入
        if(!VCodeHandler::checkType($type) || !VCodeHandler::checkFunc($func)){
            $res['info'] = '无效的输入。';
            return response()->json($res);
        }
        //检查用户是否存在(注册时需要不存在，重置时需要存在)
        $res = $this->checkExistUser($type, $target, $func);
        if(!$res['res']){
            return response()->json($res);
        }
        //生成验证码
        $makeCodeFunc = camel_case('make_'.$type.'_code');
        $res = VCodeHandler::$makeCodeFunc($target, $func);
        if(!$res['res']){
            $res['info'] = '生成验证码失败，请稍后再试。';
            return response()->json($res);
        }
        //发送验证码
        $sendCodeFunc = camel_case('send_'.$type.'_code');
        $res = $this->$sendCodeFunc($res['code'], $target, $func);
        return response()->json($res);
    }
    //检查用户状态
    private function checkExistUser($type, $target, $func){
        $user = User::where($type, $target)->first();
        $res = ['res'=>false, 'info'=>''];
        switch($func){
        case 'regist':
            $res['res'] = is_null($user);
            $res['info'] = '用户不存在。';
            break;
        case 'reset':
            $res['res'] = !is_null($user);
            $res['info'] = '用户已经存在。';
            $res['user'] = $user;
            break;
        case 'bind':
            $res['res'] = is_null($user);
            $res['info'] = '输入内容已经绑定了用户。';
            $res['user'] = $user;
            break;
        }
        return $res;
    }
    //发送短信验证码
    private function sendMobileCode($code, $mobile, $func){
        if($func == 'regist')
            $res = $this->sms->send($mobile,'有妹社区','{"code":"'.$code.'","product":"有妹社区"}','SMS_3090063');
        elseif ($func == 'reset')
            $res = $this->sms->send($mobile,'有妹社区','{"code":"'.$code.'","product":"有妹社区"}','SMS_3090061');
        elseif ($func == 'bind')
            $res = $this->sms->send($mobile,'有妹社区','{"code":"'.$code.'","product":"有妹社区"}','SMS_3090060');
        return ['res'=>true];
    }
    //发送邮件验证码
    private function sendEmailCode($code, $email, $func){
        $params = [];
        $params['name'] = '有妹用户';
        $params['token'] = $code;
        $userName = '有妹用户';
        $subject = '[有妹社区]验证邮箱';
        Mail::send('emails.validate', $params, function($message) use($email, $userName, $subject) {
            $message->to($email, $userName)->subject($subject);
        });
        return ['res'=>true];
    }
    /********************
     * 微信验证
     ********************/
    public function wechatLogin(){
        $url = WechatHandler::getWechatOAuthUrl(0);
        return redirect($url);
    }
    public function getWechatBind(){
        $openid = session('wxopenid');
        if(empty($openid)){
            return redirect('/auth/login');
        }
        return view('uset.bindwechat');
    }
    public function postWechatBind(){
        $openid = session('wxopenid');
        if(empty($openid)){
            return response()->json(['res'=>false,'info'=>'绑定失败。请稍后再试。']);
        }else{
            $value = Input::get('value');
            $password = Input::get('password');
            $type = is_numeric($value)?'mobile':'email';
            if(Auth::attempt([$type => $value, 'password' =>$password], true)){
                $user = Auth::user();
                $user->wx_open_id = $openid;
                $user->save();
                return response()->json(['res'=>true,'info'=>'绑定成功。']);
            }else{
                return response()->json(['res'=>false,'info'=>'用户名或者密码错误。绑定失败。']);
            }
        }
    }
    public function wechatRegist(){
        $url = WechatHandler::getWechatOAuthUrl(1);
        return redirect($url);
    }
    public function wxLoginCallback(Request $request){
        $code = $request->input('code');
        //未接受授权
        if(!$code){
            return false;
        }
        $bx = WechatHandler::getToken($code);
        if(is_null($bx)){
            return false;
        }
        $openid = $bx['openid'];
        $token = $bx['access_token'];
        $rtoken = $bx['refresh_token'];

        //检查用户绑定
        $user = $this->getUserByWxOpenid($openid);
        if(!is_null($user)){
            Auth::login($user);
            return redirect('/reshall');
        }else{
            session(['wxopenid'=>$openid]);
            return view('uset.bindconfirm');
            //$url = WechatHandler::getWechatOAuthUrl(1);
            //return redirect($newurl);
        }
    }
    public function wxRegistCallback(Request $request){
        $code = $request->input('code');
        //未接受授权
        if(!$code){
            return false;
        }
        $bx = WechatHandler::getToken($code);
        if(is_null($bx)){
            return false;
        }
        $openid = $bx['openid'];
        $token = $bx['access_token'];
        $rtoken = $bx['refresh_token'];

        //检查用户绑定
        $user = $this->getUserByWxOpenid($openid);
        if(!is_null($user)){
            //如果已经绑定过了就直接登录
            Auth::login($user);
            return redirect('/reshall');
        }else{
            $wxUser = WechatHandler::getUserInfo($token, $openid); 
            if(is_null($wxUser)) return false;
            //Register User
            $user = new User;
            $user->wx_open_id = $openid;
            $user->display_name = $wxUser['nickname'];
            $user->password ='';
            $user->avatar = $wxUser['headimgurl'];
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
            // 注册成功
            Auth::login($user);
            return redirect('/uset/main');//转到用户设定页面
        }
    }
    private function getUserByWxOpenid($openid){
        $user = User::where('wx_open_id', $openid)->first();
        return $user;
    }
}
