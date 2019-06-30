@extends('layouts.master')

@section('content')
@yield('serverload','')
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-delete">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">确认删除</div>
    <div class="am-modal-bd" id="my-confirm-delete-content">
      你，确定要删除吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
	<script src="/assets/js/amazeui.min.js"></script>
	<script src="/js/ym_public.js"></script>
	<script src="/js/lasyload.js"></script>
	<script src="/js/ym_longtouch.js"></script>
	<script type="text/javascript">
		var lasy = new lasyLoad();
		lasy.bindControls($(".ym_lzdiv"),'viewpath');
		var bigImg = function(obj){
		 	var $modal = $(obj).find('.am-modal-alert');
		      var $target = $(obj.target);
		      var src = $(obj).children('img').attr('src');
		      $(obj).find('.am-modal-dialog').find('img').attr('src',src);
		      if (($target).hasClass('js-modal-open')) {
		        $modal.modal();
		      } else if (($target).hasClass('js-modal-close')) {
		        $modal.modal('close');
		      } else {
		        $modal.modal('toggle');
		      }
	 	}
	</script>
	@yield('runScript','')
@stop
