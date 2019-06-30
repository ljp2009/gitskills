@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'绑定手机号'])
<link rel="stylesheet" href="/css/ym_survey_create.css">
<div class="ym_cm_card" style="border-bottom:none">
        <input type="hidden" name="oldValue" value="mobile"  value="{{$oldValue}}"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="ym_fp_row">
            <input name="value" type="number" placeholder="请输入手机号" value="{{$oldValue}}"/>
        </div>
        <div class="ym_fp_row">
            <input name="validate_code" type="number" placeholder="输入验证码" maxlength="4" value="{{old('validate_code')}}"/>
            <label class="ym_active" id="validate_btn" onclick="postValidateCode()">获取验证码</label>
        </div>
        <div class="ym_fp_row">
            <input name="password" type="password" placeholder="请输密码" />
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
        <div style="width:100%; color:#929292;font-size:1.3rem; padding:20px 10px;text-align:justify;line-height:20px">
        注意：<br />如果您是微信注册用户，并且没有设置过密码，请使用初始密码(11111111)进行绑定操作，此密码仅可以用于绑定信息和修改密码，无法用于登录。
        </div>
    <button type="button" class="ym_fp_submit" onclick="bind()">绑定手机</button>
    <button type="button" class="ym_fp_submit" onclick="unbind()">解除绑定</button>
</div>
<script type="text/javascript">
function postValidateCode(){
    var $btn = $('#validate_btn');
    $btn.removeClass('ym_active');
    $btn.text('60秒后可以重试');
    waitBtnTimeout();
    $.post('/auth/sendvalidatecode',{
        '_token':$.ymFunc.getToken(),
        'type':'mobile',
        'func':'bind',
        'value':$('input[name=value]').val()
    },function(data){
        if(data.res){
            $('#validate_btn').removeClass('ym_active');
            showError([]);
        }else{
            showError([data.info]);
        }
    }).error(function(e){
        showError(['获取验证码失败，请稍后再试。']);
    });
}
var seconds = 0;
function waitBtnTimeout(){
    var $btn = $('#validate_btn');
    var iv = setInterval(function(){
        seconds += 1;
        var lastTime =60-seconds;
        $btn.text(lastTime+'秒后可以重试');
        if(lastTime == 0){
            seconds = 0;
            $btn.text('重新获取验证码');
            $btn.addClass('ym_active');
            clearInterval(iv);
        }
    },1000);    
}
function bind(){
    $.post('/uset/bind-mobile',{
        '_token':$.ymFunc.getToken(),
        'value':$('input[name=value]').val(),
        'password':$('input[name=password]').val(),
        'code':$('input[name=validate_code]').val()
    }, function(data){
        if(data.res) $.ymFunc.back();
        else showError([data.info]);
    }).error(function(e){
        alert($(e.responseText).find(body));
    });
}
function unbind(){
    if(confirm('您确定要解除手机绑定吗？')){
        $.post('/uset/unbind-mobile',{
            '_token':$.ymFunc.getToken(),
            'password':$('input[name=password]').val()
        }, function(data){
            if(data.res) $.ymFunc.back();
            else showError([data.info]);
        });
    }
}
function showError(errArr){
    var str = "";
    for(var i=0; i<errArr.length; i++){
        if(i > 0) str += "<br />";
        str += errArr[i];
    }
    $('#err').html(str);
}
</script>
@stop

