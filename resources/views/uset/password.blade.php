@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'修改密码'])
<div id="pageContainer" class="ym_fp_container ym_active" style="height:auto;">
<input type="hidden" name="_token" value="{{csrf_token()}}" />
<div class="ym_fp_row">
    <input name="password_old" type="password" placeholder="旧的密码" maxlength="16" value=""/>
</div>
<div class="ym_fp_row">
    <input name="password" type="password" placeholder="输入新密码(最少8个字符)" maxlength="16" value=""/>
</div>
<div class="ym_fp_row">
    <input name="password2" type="password" placeholder="再次输入新密码" maxlength="16" value=""/>
</div>
<div class="ym_fp_err">
    <span id="err"></span>
</div>
<div style="width:100%; color:#929292;font-size:1.3rem; padding:20px 10px;text-align:justify;line-height:20px">
注意：<br />如果您是微信注册用户，并且没有设置过密码，请使用初始密码(11111111)进行设定密码操作，此密码仅可以用于绑定信息和修改密码，无法用于登录。
</div>
<button type="button" class="ym_fp_submit" onclick="setPwd()">提交修改</button>
<script type="text/javascript">
function setPwd(){
    var oldPwd  = $('input[name=password_old]').val();
    var newPwd  = $('input[name=password]').val();
    var newPwd2 = $('input[name=password2]').val();

    if(oldPwd == ''){
        showInfo('请输入旧密码', true);
        return;
    }
    if(newPwd.length < 8){
        showInfo('新密码至少需要需要大于8字符', true);
        return;
    }
    if(newPwd != newPwd2){
        showInfo('两次输入的密码不一致', true);
        return;
    }
    $.post('/uset/pwd', {
        '_token' : $('input[name=_token]').val(),
        'oldPwd' : oldPwd,
        'newPwd' : newPwd,
    }, function(data){
        if(!data.res){
            showInfo(data.info, !data.res);
        }else{
           $.ymFunc.goTo('/uset/main');
        }
    });
}
function showInfo(text, isErr){
    var err = $('#err');
    err.css('color', isErr?'red':'green');
    err.text(text);
}
</script>
@stop

