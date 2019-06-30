@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'none', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'提示'])
<style type="text/css">body{background-color:#f5f5f9;} </style>
<div class="ym_cm_card">
    <div class="ym_actinfo_icon">&nbsp;</div>
    <div class="ym_actinfo_label">密码设置成功</div>
    <div class="ym_actinfo_linkbox">
        <div class="ym_actinfo_desc">您已经成功重置了密码，<span style="color:red">5</span>&nbsp;秒后返回首页。</div>
    </div>
</div>
<script type="text/javascript">
    var seconds = 6;
    var iv = setInterval(function(){
        seconds -= 1;
        $(' .ym_actinfo_desc').find('span').html(seconds);
        if(seconds <=0){
            $.ymFunc.goTo('/reshall');
            clearInterval(iv);
        }
    },1000);
</script>
@stop

