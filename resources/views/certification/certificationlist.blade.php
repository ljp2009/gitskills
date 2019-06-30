@extends('layouts.list')
@section('title')
认证申请列表
@stop
@section('content')
@section('listheader')

	@include('partview.detailheader',array('hideShare'=>true))
<div class = "ym-certification-nav">
      <ul class="am-nav am-nav-tabs  am-avg-sm-3 boxes">
          <li class="am-active"><a href="/certification/list/0">全部</a></li>
          <li class=""><a href="/certification/list/1">未完成</a></li>
          <li class=""><a href="/certification/list/2">已完成</a></li>
      </ul>
 </div>
<div class="certification-list" id="certification-list">

</div>
@include('common.publishpane', array('publishEles'=>array('发起认证申请:/certification/show/guidepager')))
@stop
@parent
@section('bindlist')
//<script>
	list.bind({
		"container":"#certification-list",
		"loadSize":8,
		"type":"certification",
		@if(isset($listName))
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }}
		
	});
	//添加选中的tab样式
	function addActiveSel(){
		var path = window.location.pathname;
		$('ul').find('a').each(function(){
			var href = $(this).attr('href');
			if(path == href){
				$(this).parent().addClass("am-active");
			} else {
				$(this).parent().removeClass("am-active");
			}
			
		});
	}
	//添加选中的tab样式
	addActiveSel();
	//编辑
	function editCertifi($id){
		
		window.location.href = "/certification/edit/"+$id;
	}
	//删除
	function deleteCertifi($id, $status){
		var id  = $id;
		var sta = $status;
		$('#my-confirm-delete').modal({
        relatedTarget: this,
        onConfirm: function(options) {
            $.post('/certification/delete',
	          		{'_token':$('meta[name="csrf-token"]').attr('content'),'id':id,'status':sta},
	          		function(data){
	          			window.location.reload();
	          		}
	        )
        },
        onCancel: function() {

        }
      });
	}
	
@stop
@stop