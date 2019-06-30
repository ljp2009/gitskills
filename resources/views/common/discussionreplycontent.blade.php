@foreach($results as $res)
  <div class="am-g ym-r-header" name="oneitem">
	<div class="am-u-sm-2">
		<a href="{{$res->user->homeUrl}}">
		  <img class="am-circle" style="display: inline-block; height: 4rem; margin-top: 0.3rem "
			   src="{{$res->user->avatar}}" />
		</a>
	</div>
	<div class="am-u-sm-9" >
	  <label class="ym-ft-17 ym-c-black" style="padding: 0;margin: 0" >{{ $res->user->display_name }}</label>
		<br />
		<label class="ym-ft-09 ym-c-grey"  style="padding: 0;margin: 0"><span name="_time" thetime="{{ $res->created_at }}"></span></label>
	</div>
	<div class="am-u-sm-1">
<!-- 		<a href="/common/discuss/reply/{{$res->id}}/{{$referenceId}}"><i class="am-icon-comment-o ym-ft-15 am-fr"></i></a> -->
		@if(Auth::check()&&Auth::user()->id==$res->user_id)
		<i class="am-icon-trash-o ym-c-red ym-ft-15 am-fr" style="font-size:1.8rem" data-id="{{$res->id}}"></i>
		@endif
	</div>
	<div class="am-u-sm-12" style="padding-top: 0.5rem">
	  @if($referenceId!=$res->response_id)
	  <div>回复 {{Discussion::findDiscussion($res->response_id)->user->display_name}}</div>
	  @endif
	  <label class="ym-c-grey ym-ft-15">{{ $res->text }}</label>
	<hr style="margin:0.5rem 0 0 0" />
   </div>
   
</div>
 @endforeach

