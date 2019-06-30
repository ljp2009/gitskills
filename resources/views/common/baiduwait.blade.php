@extends('layouts.master')
@section('title',  '等待...')
<link rel="stylesheet" type="text/css" href="/css/baidueditor.css">
@section('content')
<div class="container">
	<i class="am-icon-spinner am-icon-pulse ym-baiduwait"></i>
</div>
<form id="_submitform" action="/common/baiduimport/parse2" style="display:none" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="functype" value="{{$functype}}">
	<input type="hidden" name="url" value="{{$next}}">
	<input type="hidden" name="prev" value="{{$prev}}">
	<input type="hidden" name="md5v" value="{{$md5v}}">
	<input type="hidden" name="zpm" value="{{$zpm}}">
	<input type="hidden" name="fmt" value="{{$fmt}}">
	<input type="hidden" name="zz" value="{{$zz}}">
	<input type="hidden" name="jbxx" value="{{$jbxx}}">
	<input type="hidden" name="nrms" value="{{$nrms}}">
	<input type="hidden" name="zj" value="{{$zj}}">
	<input type="hidden" name="zjt" value="{{$zjt}}">
	<input type="hidden" name="zjms" value="{{$zjms}}">
	<input type="hidden" name="type" value="{{$type}}">
</form>
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="/assets/js/jquery.min.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->
	<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('#_submitform').submit();
	});
</script>
@stop