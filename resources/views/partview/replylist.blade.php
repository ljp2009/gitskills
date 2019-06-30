  <div class="am-container ym-r-header" style="margin:0;">
	<ul class="am-nav am-nav-tabs">
	<li name="_discuss_head" class="am-active" ref="_discuss_short">
	  	<a href="javascript:void">评论</a>
	  </li>
	 </ul>
	</div>
  <div id="_discuss_short">
  	@foreach ($models as $re)
  <div class="am-g ym-r-header" style="margin:0.5rem 0;">
	<div class="am-u-sm-2">
		<a href="{{$re->user->homeUrl}}">
		  <img class="am-circle" style="display: inline-block; height: 4rem; margin-top: 0.3rem "
			   src="{{$re->user->avatar}}" />
		</a>
	</div>
	<div class="am-u-sm-9" >
	  <label class="ym-ft-17 ym-c-black" style="padding: 0;margin: 0" >{{ $re->user->display_name }}</label>
		<br />
		<label class="ym-ft-09 ym-c-grey"  style="padding: 0;margin: 0"><span name="_time" thetime="{{ $re->created_at }}"></span></label>
	</div>
	<div class="am-u-sm-1">
	  @include('common.like', array('likeObj'=>array('resourceName'=>'discussion', 'resourceId'=>$re->id, 'isLike'=>$re->iLike  )))
	</div>
	@if(Auth::check()&&Auth::user()->id==$re->user->id)
	<div class="am-u-sm-1">
	  <i class="am-icon-trash-o ym-c-red ym-ft-15 am-fr" style="font-size:1.8rem" data-id="{{$re->id}}"></i>
	</div>
	@endif
	<div class="am-u-sm-12" style="padding-top: 0.5rem">
		<pre class="ym-c-grey ym-ft-15" style="background-color:#ffffff;padding:0;border:0;margin:0;"  id="_discussion_{{$re->id}}_full">{{ $re->text }}</pre>
	</div>
	
  </div>
   <hr class="ym-border-hr ym-r-header am-cf" style="margin:0.1rem 0;border:1px solid #eeeeee;" /> 
  @endforeach
  </div>
	