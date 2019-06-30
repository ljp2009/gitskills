<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\WechatHandler;
use App\Common\RedirectCode;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserDetailStatus;
use Auth, Config;
class WechatController extends Controller
{
    /********************
     * 微信验证
     ********************/
    public function getLogin($state = RedirectCode::WECHATCODE){
        $redirectCode = new RedirectCode($state);
        $redirectCode->type == RedirectCode::TYPE_WECHAT;
        $url = WechatHandler::getBaseOAuthUrl($redirectCode->getCode());
        return redirect($url);
    }
    public function getCallback(Request $request){
        $code = $request->input('code');
        if(empty($code)){
            //用户未授权 , 返回登录页面
            return redirect('/auth/login');
        }
        $bx = WechatHandler::getToken($code);
        if(is_null($bx)){
            //授权Code有问题，返回登录页
            return redirect('/auth/login');
        }
        $openid = $bx['openid'];
        $token  = $bx['access_token'];
        $rtoken = $bx['refresh_token'];

        //尝试用OpenId登陆
        $loginStatus = $this->tryLogin($openid);
        $state = $request->input('state');
        $stateInfo = new RedirectCode($state);
        if($loginStatus){
            //登录成功，跳到指定的页面
            return redirect($stateInfo->getUrl());
        }else{
            //登录失败
            if($stateInfo->mode != RedirectCode::MODE_REGIEST){
                //不是注册过程, 引导用户进入注册过程
                $stateInfo->mode = RedirectCode::MODE_REGIEST;
                $url = WechatHandler::getUserInfoOAuthUrl($stateInfo->getCode());
                return redirect($url);
            }else{
                //注册过程,创建用户
                $wxUser = WechatHandler::getUserInfo($token, $openid);
                if(is_null($wxUser)){
                    //获取微信用户信息是吧，无法完成注册，返回登陆页
                    return redirect('/auth/login');
                }
                $this->createWechatUser($wxUser, $openid);
                return redirect($stateInfo->getUrl());
            }
        }
    }
    private function tryLogin($openid){
        $user = User::where('wx_open_id', $openid)->first();
        if(!is_null($user)){
            Auth::login($user);
            return true;
        }
        return false;
    }

    private function createWechatUser($wxUser, $openid){
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

    }

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
