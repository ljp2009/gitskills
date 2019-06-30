@extends('layouts.master')
@section('content')

<link rel="stylesheet" href="/css/formpage.css?a=1">
@yield('formrange', '')

<div class="am-modal am-modal-alert" tabindex="-1" id="_errorMessageDialog">
  <div class="am-modal-dialog">
    <div class="am-modal-bd" id="_errorMessage">
      错误
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn">确定</span>
    </div>
  </div>
</div>
<script src="/assets/js/amazeui.min.js"></script>
	<script src="/js/ym_validator.js"></script>
	<script src="/js/mobiscroll.custom-2.14.2.min.js"></script>

<script type="text/javascript">
	@yield('scriptrange', '')
</script>

@stop
