@extends('layouts.block')
@section('content')
	@section('serverLoad')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'异常'])
    <style type="text/css">
    .infoimg{ display:block; margin:100px auto auto auto; }
    .textLable{ display:block; margin:20px auto auto auto;text-align:center }
    </style>
    <img src="/imgs/404.png" class="infoimg">
    <label class="textLable">你要的东西没找到<br />稍后带你去主页</label>
	@show
	@parent
	@section('runScript')
<script type="text/javascript">
setTimeout(function(){
    $.ymFunc.goTo('/reshall');
},6000);
</script>
	@stop
@stop
