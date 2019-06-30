@if($models->count()>0)
<h5 class='ym-newest-disc-header'>最新评论</h5>
<div class="ym-newest-disc">
@foreach($models as $value)
<p class="ym_listitem"><a href="#">{{$value->user->display_name}}：</a>{{$value->text}}</p>
@endforeach
</div>
@endif
