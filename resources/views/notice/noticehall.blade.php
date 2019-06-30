@extends('layouts.list')
@section('title', '我的信箱')
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'我的信箱'])
<link rel="stylesheet"  href="/css/ym_private.css">
<div class="private-list-box" id="private">
<div class="private-list ym_listitem">
    <div class="ym_notice_letter" onclick="$.ymFunc.goTo('/notice/list/public/0')">
        <i class="ymicon-star"></i>
        <label>系统公告</label>
        @if($noticeCt > 0)
        <span>{{$noticeCt}}</span>
        @endif
    </div>
</div>
<div class="private-list ym_listitem">
    <div class="ym_notice_letter" onclick="$.ymFunc.goTo('/notice/list/like/0')">
        <i class="ymicon-heart"></i>
        <label>收到的Like</label>
        @if($likeCt > 0)
        <span>{{$likeCt}}</span>
        @endif
    </div>
</div>
<div class="private-list ym_listitem">
    <div class="ym_notice_letter" onclick="$.ymFunc.goTo('/notice/list/comment/0')">
        <i class="ymicon-comment"></i>
        <label>收到的评论</label>
        @if($commentCt > 0)
        <span>{{$commentCt}}</span>
        @endif
    </div>
</div>
<div class="private-list ym_listitem">
    <div class="ym_notice_letter" onclick="$.ymFunc.goTo('/notice/list/task/0')">
        <i class="ymicon-t-finish"></i>
        <label>任务提醒</label>
        @if($taskCt > 0)
        <span>{{$taskCt}}</span>
        @endif
    </div>
</div>
</div>
<div class="private-list-box" id="private">
</div>

@stop
@section('bindlist')
	list.bind({
		"container":"#private",
        "noneItem":"div#private>.ym_cm_list_none",
		"loadSize":8,
		"type":"notice",
		"pageIndex":{{ $page or 0 }}
	});
	
<!-- 	$('.private-list>a').on('click',function(){ -->
	function updateLetterStatus(obj){
		var $this = $(obj);
		var status = $this.attr('data-read');
		var id = $this.attr('data-id');
		var href = $.trim($this.attr('data-href'));
		if(status == 'N'){
			$.ajax({
				type:'POST',
				url:'/private/privateststus',
				data:{id:id,_token:"{{ csrf_token() }}"},
				dataType:'json',
				success:function(data){
					location.href=href;
				}
			});
		}else{
			location.href=href;
		}
	}
<!-- 	}); -->
	
@stop
