@extends('layouts.list')
@section('title', $title)
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
<link rel="stylesheet"  href="/css/ym_private.css?a=1">
<div class="private-list-box" id="private">
</div>

@stop
@section('bindlist')
	list.bind({
		"container":"#private",
        "noneItem":"div#private>.ym_cm_list_none",
		"loadSize":8,
		"type":"notice",
		"listName":"{{$type}}",
		"parentId":"{{$tid}}",
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
