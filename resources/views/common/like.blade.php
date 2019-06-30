@if(Auth::check())
	@if($likeObj['isLike'])
		<i class="am-icon-heart ym-ft-15 am-fr ym-icon-liked"></i>
	@else
	    <i id="{{$likeObj['resourceName']}}_{{$likeObj['resourceId']}}_like" class="am-icon-heart-o ym-c-red ym-ft-15 am-fr ym-icon-like" onclick="$YM_COMMON.postLike('{{$likeObj['resourceName']}}', {{$likeObj['resourceId']}}, '{{$likeObj['resourceName']}}_{{$likeObj['resourceId']}}_like')"></i> 
	@endif
@else
	<i class="am-icon-heart-o ym-ft-15 am-fr ym-icon-like"/>
@endif