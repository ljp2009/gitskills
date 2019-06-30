@extends('layouts.list')

@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$isOwner?'我的家':$model->display_name])
	@include('layouts.detail')
	<div class="am-container ym-r-header am-tab-box">
		<ul class="am-nav am-nav-tabs">
		  <li><a href="/home/list/default/{{$page}}/{{$id}}" >我的LIKE</a></li>
		  <li ><a href="/home/list/works/{{$page}}/{{$id}}">我的作品</a></li>
		  <li class="am-active"><a href="/home/list/sales/{{$page}}/{{$id}}">正在出售</a></li>
		</ul>
    </div>
	<div class="my-works my-sale">
		<div class="am-container" id="sales">
		</div>
	</div>
@stop

@section('bindlist')
//<script>
	list.bind({
		"container":"#sales",
		"type":"home",
		"listName":"{{$listName}}",
		"parentId":{{ $id }},
		"pageIndex":{{ $page }},
	});
	  $('#doc-prompt-toggle').on('click', function() {
	  	var $action = $(this).attr('data-action');
	  	var $id = $(this).attr('data-id');
	  	var $this = $(this);
	    $('#my-prompt').modal({
	      relatedTarget: this,
	      onConfirm: function(e) {
<!-- 	        alert('你输入的是：' + e.data || '') -->
	      	var gold = $.trim(e.data);
<!-- 	      	alert(gold); -->
	      	if(gold == '' || gold == 0){
	      	  myAlert('请输入金币');
	      	  return false;
	      	}
	        $.ajax({
				type:'POST',
				url:'/user/relation',
				data:{id:$id,action:$action,gold:e.data,_token:"{{ csrf_token() }}"},
				dataType:'json',
				success:function(data){
					if(data.code == 1){
						$('#my-prompt').css('opacity','0');
						$('.am-dimmer').css('display','none');
					}else if(data.code < 0){
						myAlert(data.msg);
					}
				}
			});
	      },
	      onCancel: function(e) {
<!-- 	        alert('不想说!'); -->
	      }
	    });
	  });
	
	function myAlert($msg){
		var $modal = $('#my-alert');
	      var $target = $modal;
	      if (($target).hasClass('js-modal-open')) {
	        $modal.modal();
	      } else if (($target).hasClass('js-modal-close')) {
	        $modal.modal('close');
	      } else {
	        $modal.modal('toggle');
	      }
      	  $('#my-alert .am-modal-bd').text($msg);
	}
	
	<!-- 发私信 -->
	$('#sendPrivateMsg').on('click',function(e){
		$this = $(this);
		var $class = $this.attr('class');
<!-- 		alert($class); -->
		if($class == 'sendMsg'){
			var $opacity = $('.post-comment').css('opacity');
			if($opacity == 0){
				$('.post-comment').animate({'opacity':'1','bottom':'0'});
			}else{
				$('.post-comment').animate({'opacity':'0','bottom':'-42'});
			}
		}else{
			location.href="/private/list/default/{{ $page }}/{{$id}}";
		}
		e.stopPropagation();//阻止冒泡
	});
	$('.post-comment #sendMsg').on('click',function(){
		var $this = $(this);
		var $msg = $.trim($this.parent().find('input[name="privatemsg"]').val());
		if(!$msg){
			myAlert('输入的内容不能为空！');
			return false;
		}
		$.ajax({
			type:'POST',
			url:'/private/privateletter',
			data:{id:{{$id}},msg:$msg,_token:"{{ csrf_token() }}"},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					$('.post-comment').animate({'opacity':'0','bottom':'-42'});
				}else if(data.code < 0){
					myAlert(data.msg);
				}
			}
		});
	});
	
	$('.post-comment').siblings().click(function(){
        $('.post-comment').animate({'opacity':'0','bottom':'-42'});
    });
	
@stop

