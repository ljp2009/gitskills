@extends('layouts.submit')
@section('title',  trans('auth.login'))

@section('head')
    <link rel="stylesheet" href="/css/ym_login.css">
@stop

@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'登录'])

<div class="am-container">
    <p class="login-title">欢迎来到有妹社区！~</p>
    <form method="post" id="formLogin" action="/auth/login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="redirectCode" value="{{$redirectCode or ''}}" />
         <div class="am-input-group  login-capable ">
              <i class="ymicon-user2"></i>
              <input type="text" class="am-form-field ym-publish-field login-user" maxlength='50' validate="phoneOrMail" name="uname" placeholder="请输入邮箱或者手机号">
        </div>
        <div class="am-form-group login-capable" style="margin-bottom:0;">
          <i class=" ymicon-lock" ></i>
          <input type="password" class="am-form-field ym-publish-field login-password" validate="required" name="password"  errormessage="密码不能为空" placeholder="请输入密码">
        </div>
        <div id="_errorMessageLabel" class="ym_submit_error_msg">
          请输入密码
        </div>
        <div class="am-form-group am-cf ">
            <label class="am-checkbox-inline login-status">
                   <input type="checkbox" name="remember" value="remember"  validate="required">记住登录状态
            </label>
            <a class="am-fr login-status-right" href="/auth/reset">忘记密码 ?</a>
        </div>
        <input type="hidden" name = "isAjaxPost">
    </form>
    <div class="login-btn">
        <button type="button" class="am-btn am-btn-warning am-btn-block am-radius" onclick="check()">登录</button>
        <div>
            <button type="button" class="am-btn am-btn-warning am-btn-block am-radius login-register"
              id="regist_btn" onclick= "$.ymFunc.goTo('/regist/show/{{$redirectCode or ""}}')">快速注册</button>
            <button type="button" class="am-btn am-radius login-wechat" style="display:none" id="wechat_btn">
                <i class="ymicon-weixin" style="color:#fff;"></i> <a href="/wechat/login/{{$redirectCode or ""}}">&nbsp;&nbsp;微信登录</a>
            </button>
            <button type="button" class="am-btn am-radius login-wechat" style="display:none" id="qq_btn">
                <i class="ymicon-qq" style="color:#fff;"></i> <a href="/qq/login/{{$redirectCode or ""}}">&nbsp;&nbsp;QQ登录</a>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
function check(){
    var err = '';
    var uname = $('input[name="uname"]').val();
    var pwd   = $('input[name="password"]').val();
    var redirectCode = $('input[name="redirectCode"]').val();

    if(uname == ''){
        err = '请输入用户手机号或邮箱';
    }
    if(pwd == ''){
        err += ((err==''?'':'<br />')+'请输入密码');
    }
    if(err != ''){
        $('#_errorMessageLabel').html(err);
        $('#_errorMessageLabel').show();
    }else{
        $('#_errorMessageLabel').hide();

        $.post('/auth/login',{
            'uname':uname,
            'password':pwd,
            'redirectCode':redirectCode,
            '_token':$.ymFunc.getToken()
        }, function(data){
            if(data.res){
                $.ymFunc.goTo(data.url);
            }else{
                $('#_errorMessageLabel').html(data.info);
                $('#_errorMessageLabel').show();
            }
        });
    }
}
$(function(){
    if(!$.ymFunc.checkWechat()){
        $('#wechat_btn').hide();
        $('#qq_btn').show();
    }else{
        $('#wechat_btn').show();
        $('#qq_btn').hide();
    }
});
</script>

@stop
