@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'绑定微信'])
<link rel="stylesheet" href="/css/ym_survey_create.css">
<div class="ym_cm_card" style="border-bottom:none">
        <div class="ym_fp_row">
            <input name="value" type="text" placeholder="请输注册的手机号或者邮箱" value=""/>
        </div>
        <div class="ym_fp_row">
            <input name="password" type="password" placeholder="请输密码" />
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    <button type="button" class="ym_fp_submit" onclick="bind()">绑定微信</button>
</div>
<script type="text/javascript">
function bind(){
    $.post('/auth/weixin/bind',{
        '_token':$.ymFunc.getToken(),
        'value':$('input[name=value]').val(),
        'password':$('input[name=password]').val()
    }, function(data){
        if(data.res) $.ymFunc.goTo('/uset/main');
        else showError([data.info]);
    }).error(function(e){
        alert($(e.responseText).find(body));
    });
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
