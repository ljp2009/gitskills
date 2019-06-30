@extends('layouts.master')
@section('content')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>isset($right)?$right:'post', 'pageTitle'=>$title])
<style>body{background:#ffffff}</style>
<div class="am-container" style="padding:0 2rem;">
@yield('formrange', '')
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="_errorMessageDialog">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd" >
      <div class="am-g">
        <div class="am-u-sm-3 am-u-md-3 am-u-lg-3"><img src='/imgs/headers/default.jpg' style='width:90%; height:90%'/></div>
        <div class="am-u-sm-8 am-u-md-8 am-u-lg-8" id="_errorMessage" style="text-align:left">错误</div>
        <div class="am-u-sm-1 am-u-md-1 am-u-lg-1">&nbsp;</div>
      </div>
    </div>
  </div>
</div>
	<script src="/assets/js/amazeui.min.js"></script>
	<script src="/js/ym_public.js"></script>
	<script src="/js/ym_validator.js?a=1"></script>
	<script src="/js/jquery.wallform.js"></script>
	<script src="/js/ym_imageupload.js"></script>
	<script src="/js/ym_dynamicattr.js"></script>
	<script src="/js/ym_attachtag.js"></script>
	<script src="/js/ym_attachupload.js"></script>
	<script src="/js/mobiscroll.custom-2.14.2.min.js"></script>
	<script src="/js/area.js"></script>
    @yield('scriptref', '')
	<script type="text/javascript">
		@yield('scriptrange', '')
	</script>
@stop
