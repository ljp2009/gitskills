@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'微信登录'])
<style type="text/css">body{background-color:#f5f5f9;} </style>
<div class="ym_cm_card">
    <div class="ym_actinfo_icon">&nbsp;</div>
    <div class="ym_actinfo_label">微信登录</div>
    <div class="ym_actinfo_linkbox">
        <div class="ym_actinfo_desc">
        当前微信没有与妹社区帐号绑定过， 您可以:<br />
        <a href="/auth/weixin/bind">&nbsp;&nbsp;绑定一个已有的有妹帐号</a>
        <a href="/auth/weixin/regist">&nbsp;&nbsp;创建一个新的帐号</a></div>
        <a href="/auth/login">&nbsp;&nbsp;返回登录页面</a>

    </div>
</div>
<script type="text/javascript">

</script>
@stop
