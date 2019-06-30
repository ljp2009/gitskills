<h5 class='ym-newest-disc-header'>最新评论</h5>
<div class="ym-newest-disc" style="margin-bottom:1rem">
@foreach($models as $value)
<p><a href="#">{{$value->user->display_name}}：</a>{{$value->text}}</p>
@endforeach
@if($models->count()<=0)
	<p style="font-size:1rem">暂无评论</p>
@endif
</div>