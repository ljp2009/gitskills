@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'用户注册'])
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
<input type="hidden" id="redirectCode" value="{{$redirectCode or ''}}" />
<div id="pageContainer" class="ym_fp_container ym_active" style="height:auto;">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <div class="ym_fp_row">
        <input name="userName" type="text" placeholder="请输入手机号或者邮箱" value="" maxlength="36"/>
    </div>
    <div class="ym_fp_row">
        <input name="validateCode" type="text" placeholder="输入验证码" value="" maxlength="6"/>
        <label class="ym_active" id="validate_btn" onclick="sendCode()">获取验证码</label>
    </div>
    <div class="ym_fp_err">
        <span id="err"></span>
    </div>
    <button type="button" class="ym_fp_submit" onclick="validate()">验证帐号</button>
<script type="text/javascript">
function sendCode(){
    if($('#validate_btn').attr('disabled') == 'disabled'){
        return;
    }
    var userName = $('input[name=userName]').val();
    if(userName == ''){
        showInfo('请填写注册帐号', true);
        return;
    }
    var patterns = new Object(); 
    patterns.email = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
    patterns.mobile = /^1[34578]\d{9}$/;
    if(!patterns.email.test(userName) && !patterns.mobile.test(userName)){
        showInfo('帐号格式有误，请填写手机号或者邮箱', true);
        return;
    }
    waitBtnTimeout();
    $.post('/regist/send-code', {
        '_token'  : $('input[name=_token]').val(),
        'userName': userName,
    }, function(data){
        showInfo(data.info, !data.res);
    });
}
function validate(){
    var userName = $('input[name=userName]').val();
    var validateCode = $('input[name=validateCode]').val();
    if(userName == ''){
        showInfo('请填注册帐号', true);
        return;
    }
    if(!/^\d{6}$/.test(validateCode)){
        showInfo('请填写有效验证码', true);
        return;
    }
    $.post('/regist/validate', {
        '_token'      : $('input[name=_token]').val(),
        'userName'    : userName,
        'validateCode': validateCode,
    }, function(data){
        if(!data.res){
            showInfo(data.info, !data.res);
        }else{
            $('#pageContainer').html(data.info);
            showNewPage(data.info);
        }
    });
}
var seconds = 0;
function waitBtnTimeout(){
    var maxTime = 60;
    var $btn = $('#validate_btn');
    $btn.removeClass('ym_active');
    $btn.attr('disabled', 'disabled');
    $btn.text(maxTime+'秒后可以重试');
    var iv = setInterval(function(){
        seconds += 1;
        var lastTime =maxTime-seconds;
        $btn.text(lastTime+'秒后可以重试');
        if(seconds >= maxTime ){
            seconds = 0;
            $btn.text('重新获取验证码');
            $btn.addClass('ym_active');
            $btn.removeAttr('disabled');
            clearInterval(iv);
        }
    },1000);    
}
</script>
</div>
<script type="text/javascript">
function showInfo(text, isErr){
    var err = $('#err');
    err.css('color', isErr?'red':'green');
    err.text(text);
}
function showNewPage(htmlStr){
    $('#pageContainer').html(htmlStr);
}
</script>
@stop

