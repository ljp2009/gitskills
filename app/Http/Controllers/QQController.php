<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\QQHandler;
use App\Common\RedirectCode;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserDetailStatus;
use Auth, Config;
class QQController extends Controller
{
    /********************
     * 微信验证
     ********************/
    public function getLogin(Request $request, $state = RedirectCode::QQCODE){
        $redirectCode = new RedirectCode($state);

        $redirectCode->type == RedirectCode::TYPE_QQ;
        $redirectCode->generateToken();
        $code = $redirectCode->getCode();
        $token = $redirectCode->getToken();

        $tokenCode = $code.'-'.$token;
        $request->session()->put('tokenCode', $tokenCode);

        $url = QQHandler::getBaseOAuthUrl($tokenCode);
        return redirect($url);
    }
    public function getCallback(Request $request){
        $code = $request->input('code');
        $state = $request->input('state');
        $tokenCode = $request->session()->get('tokenCode');

        if($state == $tokenCode){
            $url = QQHandler::getAccessTokenUrl($code, $state);
            $cx = file_get_contents($url) ;
            $lpos = strpos($cx, "error");
            if($lpos > 0){
                return redirect('/');
            }
            $tmpArr = explode('&', $cx);
            $accessToken = explode('=', $tmpArr[0]);
            $url = QQHandler::getOpenIdOAuthUrl($accessToken[1]);
            $cx = file_get_contents($url) ;
            $lpos = strpos($cx, "(");
            $rpos = strrpos($cx, ")");
            $cx  = substr($cx, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($cx);
            if (isset($msg->error)) {
                return redirect('/');
            }
            $openid = $msg->openid;
            if($this->tryLogin($openid)){
                return redirect('/reshall');
            }else{
                $url = QQHandler::getUserInfoOAuthUrl($accessToken[1], $openid);
                $cx = file_get_contents($url) ;
                $qqUser = json_decode($cx);
                $user = $this->createQQUser($qqUser, $openid);
                return redirect('/reshall');
            }
        }else{
            return redirect('/');
        }
    }
    private function tryLogin($openid){
        $user = User::where('qq_open_id', $openid)->first();
        if(!is_null($user)){
            Auth::login($user);
            return true;
        }
        return false;
    }

    private function createQQUser($qqUser, $openid){
        $user = new User;
        $user->qq_open_id = $openid;
        $user->display_name = $qqUser->nickname;
        $user->password ='';
        $user->avatar = $qqUser->figureurl_qq_2;
        $user->save();

        $genderArr = ['男'=>'2000201', '女'=>'2000202'];
        $userId   = $user->id;
        $userInfo = new UserInfo();
        $userInfo->user_id = $userId;
        $userInfo->sex       = array_key_exists($qqUser->gender, $genderArr)?$genderArr[$qqUser->gender] : '2000200';
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

        return $user;
    }
}
