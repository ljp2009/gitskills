@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'none', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'提示'])
<style type="text/css">body{background-color:#f5f5f9;} </style>
<div class="ym_cm_card">
    <div class="ym_actinfo_icon">&nbsp;</div>
    <div class="ym_actinfo_label">注册成功</div>
    <div class="ym_actinfo_linkbox">
        <div class="ym_actinfo_desc">您已经成功注册了有妹社区帐号，<span style="color:red">10</span>&nbsp;秒后我们将引导您设置您的帐号。</div>
    </div>
</div>
<script type="text/javascript">
    var seconds = 11;
    var iv = setInterval(function(){
        seconds -= 1;
        $(' .ym_actinfo_desc').find('span').html(seconds);
        if(seconds <=0){
            $.ymFunc.goTo('/uset/main');
            clearInterval(iv);
        }
    },1000);
</script>
@stop

