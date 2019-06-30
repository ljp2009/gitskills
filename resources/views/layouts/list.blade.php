@extends('layouts.master')
@section('content')
@include('partview.imgpreview')
<link href="/css/list.css" rel="stylesheet" />
<div id="listDataDiv">
@yield('listcontent','')
</div>
<div class="ym_list_bottom_loading" >
  <i class="ymicon-switch" id="listLoadingBtn"></i>
	<span id="listControlBtn"style="padding:1rem 0rem;height:30px;line-height: 30px;">加载中</span>
</div>
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
<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示</div>
    <div class="am-modal-bd" id="my-confirm-delete-content">
      你，确定要删除吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/js/listload.js?a=1"></script>
<script type="text/javascript">
	var list = new listLoad();
	@yield('bindlist','');
    $(document).ready(function(){
        list.begin();
    });
</script>

@stop
