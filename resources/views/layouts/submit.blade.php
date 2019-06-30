@extends('layouts.master')

@section('content')

@yield('formrange', '')

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
	<script src="/js/ym_validator.js"></script>

    @yield('scriptref', '')
	<script type="text/javascript">
		@yield('scriptrange', '')
	</script>
@stop
