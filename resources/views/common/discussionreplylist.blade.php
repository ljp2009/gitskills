@extends('layouts.list')

@section('listheader')
  @include('partview.detailheader')
  <div class="am-g ym-r-header">
  <div class="am-u-sm-2">
	   <a href="{{$discussion->user->homeUrl}}">
	    	<img class="am-circle" style="display: inline-block; height: 4rem; margin-top: 0.3rem "
	       src="{{$discussion->user->avatar}}" />
       </a>
  </div>
  <div class="am-u-sm-9" >
    <label class="ym-ft-17 ym-c-black" style="padding: 0;margin: 0" >{{ $discussion->user->display_name }}</label>
    <br />
    <label class="ym-ft-09 ym-c-grey"  style="padding: 0;margin: 0"><span name="_time" thetime="{{ $discussion->created_at }}"></span></label>
  </div>
  <div class="am-u-sm-1">
    <a href="/common/discuss/reply/{{$discussion->id}}/{{$discussion->id}}"><i class="am-icon-comment-o ym-ft-15 am-fr"></i></a>
  </div>
  <div class="am-u-sm-12" style="padding-top: 0.5rem">
    <label class="ym-c-grey ym-ft-15">{{ $discussion->text }}</label>
  </div>
</div>
  <div id="container">
  </div>
@stop

@section('bindlist')
  list.bind({
    "container":"#container",
    "type":"common/discuss/reply",
    "listName":"{{$discussion->id}}",
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
		"deleteInfo":"您确定删除回复吗?",
		@if(isset($deleteroute))
		"deleteRoute":'{{$deleteroute}}',
		@endif
	});
 	longTouch.delete_obj();
@stop