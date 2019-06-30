<!--显示当前用户创建的任务-->
@extends('layouts.list')

@section('listheader')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css">
	@include('partview.hallheader')
	
@stop

@section('bindlist')
	list.bind({
		"type":"{{ $type }}",
		"parentId":{{ $uid }},
		@if(isset($listName)) 
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }}
	});
	
   function acceptRequest(obj,status,type,id,userID){
   		var $this = $(obj);
   		var status = status;
   		var type = type;
   		var id = id;
   		var userID = userID;
   		$.ajax({
   			type:'POST',
   			url:'/task/joinreqeust/accept',
   			data:{id:id,type:type,userId:userID,_token:"{{ csrf_token() }}"},
   			dataType:'json',
   			success:function(data){
   				$this.addClass('am-disabled');
   				$this.prev().text('弃用').addClass('am-btn-danger').attr('data-status',data.parm.status);
   				location.reload();
   			}
   		});
   }
   function rejectRequest(obj,status,type,id,userID){
	  	var $this = $(obj);
   		var status = status;
   		var type = type;
   		var id = id;
   		var userID = userID;
	    $('#my-prompt').modal({
	      relatedTarget: this,
	      onConfirm: function(e) {
<!-- 	        alert('你输入的是：' + e.data || '') -->
	      	var reject = $.trim(e.data);
<!-- 	      	alert(gold); -->
	      	if(reject == ''){
	      	  myAlert('请输入拒绝理由');
	      	  return false;
	      	}
	        $.ajax({
				type:'POST',
				url:'/task/joinreqeust/reject',
				data:{id:id,type:type,userId:userID,status:status,reject:e.data,_token:"{{ csrf_token() }}"},
				dataType:'json',
				success:function(data){
					$this.addClass('am-disabled');
					$this.next().addClass('am-disabled');
					location.reload();
				}
			});
	      },
	      onCancel: function(e) {
	      }
	    });
	  };
@stop