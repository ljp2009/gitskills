@extends('layouts.list')

@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$isOwner?'我的家':$model->display_name])
	@include('layouts.detail')
	<link href="/css/ym_dialog.css" rel="stylesheet" />
    <script src="/assets/common/commonDialog.js"></script>
	<div class="am-container ym-r-header am-tab-box">
		<ul class="am-nav am-nav-tabs">
		  <li ><a href="/home/list/default/{{$page}}/{{$id}}" >我的LIKE</a></li>
		  <li class="am-active"><a href="/home/list/works/{{$page}}/{{$id}}">我的作品</a></li>
		</ul>
    </div>
	<div class="my-works">
        <div  class="am-g" id="works" style="padding-top:1rem;">
        </div>
	</div>
<div style="display:none">
    <input type='button' id='production_del' value=''>
</div>
@if($isOwner)
@include('partview.listaddbar',['url'=>'/pub/create'])
@endif
   <script>
    //删除
    function deleteProduction(id){
        $("#production_del").val(id);
        $("#production_del").click();
    }
    //绑定删除按钮
    $("#production_del").commonDialog({
            type:'confirmAndCancelDialog',
            content:'您，确定要删除这条记录吗，此操作会同步删除掉同人/周边/我的作品中的相关信息？'
        })
        .bind('confirmAndCancelDialog', function(res){
            var id = $("#production_del").val();
            //调用删除函数
            $.post('/pub/delete-ajax',{
                'id':id,
                '_token':$.ymFunc.getToken()
            },function(data){
                if(data.res){
                    window.location.reload();
                }
            });
            console.log('production_id:'+$id);
        }, null);

   </script>
<script src="/js/worklistload.js"></script>
@stop

@section('bindlist')
//<script >
function doLikeProduction(id,countControl){
    $.post('/common/likeAndCount',{
        '_token':$('meta[name="csrf-token"]').attr('content'),
        'resource':'user_production',
        'resourceId':id},
        function(data){
           $(countControl).parent().html('<i class="am-icon-heart" style="color:red"></i> <span>'+data+'</span>');
        })
        .error(function(e){
            alert(e.responseText);
        });
}
	list.bind({
		"container":"#works",
		"type":"home",
		"listName":"{{$listName}}",
		"parentId":{{ $id }},
		"pageIndex":{{ $page }},
		"controlBtn":"#listControlBtn"
	});
	$('#publishButton').on('click',function(){
		@if($isOwner)
	    var html = '<a href="/pub/create" class="am-btn am-btn-secondary btn-loading-example" style="width:100%;border-radius:5px;" type="button">发布作品</a>';
		$('#_publishFunctionPane .am-modal-bd').css('padding','0').html(html);
		$('#_publishFunctionPane .am-modal-hd').css('display','none');
		$('#_publishFunctionPane .am-modal-dialog').css('border','none');
		@endif
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
				if(data.code == 1){
					$this.addClass('am-disabled');
				}
			}
		});
	});

	  $('#doc-prompt-toggle').on('click', function() {
	  	var $action = $(this).attr('data-action');
	  	var $id = $(this).attr('data-id');
	  	var $this = $(this);
	    $('#my-prompt').modal({
	      relatedTarget: this,
	      onConfirm: function(e) {
	      	var gold = $.trim(e.data);
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

