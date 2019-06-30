@extends('layouts.list')

@section('listheader')
  @include('partview.detailheader')
  <div class="am-g ym-r-header am-container">
  @if ($isshort)
	短评论
  @else
	长评论
  @endif
  </div>
  <div id="container">
  </div>
  
@stop

@section('bindlist')
  list.bind({
    "container":"#container",
    "type":"common/discuss/{{$resourcename}}",
    @if($isshort)
    "listName":"short",
    @else
    "listName":"long",
    @endif
    "parentId":{{ $resourceid }},
    "pageIndex":{{ $page }},
    "itemFeature":"[name=oneitem]",
    "extraFns":[function(){
       $YM_COMMON.applyTimeToObjects();
     }]
  });
  
  var longTouch = new longTouch;
	longTouch.bind({
		"parentContainer":"#container",
		"container":".am-icon-trash-o",
		"token":"{{ csrf_token() }}",
		"deleteInfo":"您确定删除该评论吗?",
		@if(isset($deleteroute))
		"deleteRoute":'{{$deleteroute}}',
		@endif
	});
 	longTouch.delete_obj();
@stop
