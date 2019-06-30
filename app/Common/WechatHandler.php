<?php
/*
 * 邮件发送类，所有邮件发送的出口
* */
namespace App\Common;

use Config;
class WechatHandler{
    const SCOPE_BASE = 'snsapi_base';
    const SCOPE_USERINFO = 'snsapi_userinfo';
    /*新接口*/
    public static function getBaseOAuthUrl($state){
        return self::getOAuthUrl($state, self::SCOPE_BASE);
    }
    public static function getUserInfoOAuthUrl($state){
        return self::getOAuthUrl($state, self::SCOPE_USERINFO);
    }
    private static function getOAuthUrl($state, $scope){
        $weixinSet = Config::get('app.weixin');
        $domain    = Config::get('app.domain');
        $appId     = $weixinSet['AppId'];
        $url       = urlencode($domain."/wechat/callback");
        $newurl = "https://open.weixin.qq.com/connect/oauth2/authorize".
                    "?appid=$appId".
                    "&redirect_uri=$url".
                    "&response_type=code".
                    "&scope=$scope".
                    "&state=$state".
                    "#wechat_redirect";
        return $newurl;
    }
    /*旧接口*/
    public static function getWechatOAuthUrl($urlState=0){
        $weixinSet = Config::get('app.weixin');
        $appId = $weixinSet['AppId'];
        $callbackDomain = $weixinSet['CallbackDomain'];
        $callbackType =self::getCallBackType($urlState);
        $url = urlencode($callbackDomain."weixin/".$callbackType."-callback");
        $state  = $urlState;
        $scope = $state==1?'snsapi_userinfo':'snsapi_base';
        $newurl = "https://open.weixin.qq.com/connect/oauth2/authorize".
                    "?appid=$appId".
                    "&redirect_uri=$url".
                    "&response_type=code".
                    "&scope=$scope".
                    "&state=$state".
                    "#wechat_redirect";
        return $newurl;
    }

    public static function getToken($code){
        $weixinSet = Config::get('app.weixin');
        $appid = $weixinSet['AppId'];
        $secret =$weixinSet['AppSecret'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token".
                 "?appid=$appid".
                 "&secret=$secret".
                 "&code=$code".
                 "&grant_type=authorization_code";
        $cx = file_get_contents($url) ;
        $bx = json_decode($cx,true) ;
        //获取openid失败
        if(array_key_exists('errcode', $bx)) return null;
        return $bx;
    }
    public static function refreshToken($rtoken){
        $weixinSet = Config::get('app.weixin');
        $appid = $weixinSet['AppId'];
        $secret =$weixinSet['AppSecret'];
        $rurl = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$rtoken ;
        $rr = file_get_contents($rurl) ;
        $rr = json_decode($rr,true) ;
        if(array_key_exists('errcode', $rr)) return null;
        return $rr;
    }
    public static function getUserInfo($token, $openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN" ;
        $xv = file_get_contents($url) ;
        //file_put_contents('xv.txt', $xv);
        /*$xv返回数据格式 {
            "openid":"XXX",
            "nickname":"Mini_Ren",
            "sex":1,
            "language":"zh_CN",
            "city":"郑州",
            "province":"河南",
            "country":"中国",
            "headimgurl":"",
            "privilege":[]
        } */
        $xv = json_decode($xv,true) ;
        if(array_key_exists('errcode', $xv)) return null;
        return $xv;
    }
    private static function getCallBackType($state){
        $map = [
            0=>'login',
            1=>'regist',
            2=>'bind',
            3=>'task',
        ];
        if(array_key_exists($state, $map)){
            return $map[$state];
        }
        else{
            return 'error';
        }
    }
}
?>
