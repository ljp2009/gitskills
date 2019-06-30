@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'快速注册'])
<div class="ym_tabswitchbar">
    <ul class="ym_avg_2">
        <li id="tab_mobile" class="ym_active" onclick="switchTab('mobile')"> 手机注册 </li>
        <li id="tab_email" onclick="switchTab('email')"> 邮箱注册 </li>
    </ul>
</div>
<div class="ym_fp_container ym_active" style="margin-top:14px">
    <form method="post" action="/auth/register" onsubmit="return validate()">
        <input type="hidden" name="type" value="mobile"  value="{{old('type')}}"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="ym_fp_row">
            <input name="value" type="number" placeholder="请输入手机号" value="{{old('value')}}"/>
        </div>
        <div class="ym_fp_row">
            <input name="validate_code" type="number" placeholder="输入验证码" maxlength="4" value="{{old('validate_code')}}"/>
            <label class="ym_active" id="validate_btn" onclick="postValidateCode()">获取验证码</label>
        </div>
        <div class="ym_fp_row">
            <input name="password" type="password" placeholder="请输密码" />
        </div>
        <div class="ym_fp_row">
            <input name="password2" type="password" placeholder="请再次输密码" />
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    <button type="submit" class="ym_fp_submit">注册</button>
    </form>
</div>
<script type="text/javascript">
function switchTab(name){
    var active = name;
    var deactive = name == 'mobile'?'email':'mobile';
    var placeholder = name == 'mobile'?'请输入手机号':'请输入电子邮件地址';
    var inputType = name == 'mobile'?'number':'text';
    $('.'+deactive).hide();
    $('.'+active).show();
    $('#tab_'+active).addClass('ym_active');
    $('#tab_'+deactive).removeClass('ym_active');
    $('input[name=type]').val(name);
    $('input[name=value]').val('');
    $('input[name=value]').attr('placeholder', placeholder);
    $('input[name=value]').attr('type', inputType);
}
function validate(){
    var errArr = [];
    validateAccountFormat(errArr);
    validateCode(errArr);
    validatePwd(errArr);
    showError(errArr);
    return errArr.length == 0;
}
function validateAccountFormat(errArr){
    var type = getType();
    var fieldName = type == 'mobile'?'手机':'邮箱';
    var $field = $('input[name=value]');
    var value = $field.val();
    var isErr = false;
    if(value == ''){
        errArr.push('请填写注册的'+fieldName+'。');
        isErr = true;
    }else{
        if(type == 'mobile'){
            if(!$.ymValidator.checkPhone(value)){
                n
                errArr.push('电话号码格式错误。');
                isErr = true;
            }
        }
        if(type == 'email'){
            if(!$.ymValidator.checkEmail(value)){
                errArr.push('邮箱格式错误。');
                isErr = true;
            }
        }
    }
    if(isErr){
        markFieldStatus($field, 'err');
    }else{
        markFieldStatus($field, 'ok');
    }
}

function validateCode(errArr){
    var $field = $('input[name=validate_code]');
    var value = $field.val();
    if($.trim(value).length != 6){
        errArr.push('请输入６位的验证码。');
        markFieldStatus($field, 'err');
    }else{
        markFieldStatus($field, 'ok');
    }
}

function validatePwd(errArr){
    var $pwdField = $('input[name=password]');
    var $pwdField2 = $('input[name=password2]');
    var pwdValue = $pwdField.val();
    var pwdValue2 = $pwdField2.val();

    markFieldStatus($pwdField, 'none');
    markFieldStatus($pwdField2, 'none');
    if(pwdValue.length < 8){
        errArr.push('请输入至少８位的密码。');
        markFieldStatus($pwdField, 'err');
        return;
    }
    markFieldStatus($pwdField, 'ok');
    if(pwdValue != pwdValue2){
        errArr.push('两次输入的密码不一致。');
        markFieldStatus($pwdField, 'err');
        markFieldStatus($pwdField2, 'err');
        return;
    }
    markFieldStatus($pwdField2, 'ok');
}

function postValidateCode(){
    var $btn = $('#validate_btn');
    $btn.removeClass('ym_active');
    $btn.text('60秒后可以重试');
    waitBtnTimeout();
    $.post('/auth/sendvalidatecode',{
        '_token':$.ymFunc.getToken(),
        'type':getType(),
        'func':'regist',
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
        if(seconds == 10){
            seconds = 0;
            $btn.text('重新获取验证码');
            $btn.addClass('ym_active');
            clearInterval(iv);
        }
    },1000);    
}
function getType(){
    return $('input[name=type]').val();
}
function showError(errArr){
    var str = "";
    for(var i=0; i<errArr.length; i++){
        if(i > 0) str += "<br />";
        str += errArr[i];
    }
    $('#err').html(str);
}

function markFieldStatus(field, status){
    $row = $(field).parent();
    $row.removeClass('error');
    $row.removeClass('validated');
    switch(status){
    case 'ok':
        $row.addClass('validated');
        break;
    case 'err':
        $row.addClass('error');
        break;
    }
}
@if(!is_null(Session::get('err')))
showError(['{{Session::get("err")}}']);
@endif
</script>
@stop

