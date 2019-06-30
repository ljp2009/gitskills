@extends('layouts.list')

@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$isOwner?'我的家':$model->display_name])
	@include('layouts.detail')
	<div class="am-container ym-r-header am-tab-box">
		<ul class="am-nav am-nav-tabs">
		  <li class="am-active"><a href="/home/list/default/0/{{$id}}" >我的LIKE</a></li>
		  <li ><a href="/home/list/works/0/{{$id}}">我的作品</a></li>
		</ul>
    </div>
	<div class="am-container-box my-works">
		<div class="am-container my-favorite" >
			@if($num > 0)
			<div class="time-zone"></div>
			@endif
			<div id="default" style="padding-top:10px">
			</div>
		</div>
	</div>
@stop

@section('bindlist')
//<script>
	list.bind({
		"container":"#default",
		"type":"home",
		"listName":"{{$listName}}",
		"parentId":{{ $id }},
		"pageIndex":{{ $page }},
	});
	<!-- 操作 -->
	$('.nodisable').on('click',function(){
		var $this = $(this);
		var $id = $this.attr('data-id')*1;
		var $action = $this.attr('data-action');
		$.ajax({
			type:'POST',
			url:'/user/relation',
			data:{id:$id,action:$action,_token:"{{ csrf_token() }}"},
			dataType:'json',
			success:function(data){

				//关注成功
				if(data.code == 1){
					$this.html('<i class="am-icon-eye"></i>取消');
					
					$('.am-fans-num').html(data.value);
				//取消成功
				} else if(data.code == 2){
					$this.html('<i class="am-icon-eye"></i>关注');
				}
				$('.am-fans-num').html(data.value);
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

