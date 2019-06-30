@if(Auth::check())
	@if($likeObj['isLike'])
		<i class="am-icon-heart ym-icon-liked"></i>
	@else
	    <i id="{{$likeObj['resourceName']}}_{{$likeObj['resourceId']}}_like" class="ym-icon-like" onclick="$YM_COMMON.postLikeForList('{{$likeObj['resourceName']}}', {{$likeObj['resourceId']}}, '{{$likeObj['resourceName']}}_{{$likeObj['resourceId']}}_like')"></i> 
	@endif
@else
	<i class="am-icon-heart-o ym-icon-like"/>
@endif