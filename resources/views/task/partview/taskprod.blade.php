@foreach($list as $prod)
<div class="ym-task-show">
	<img class="am-img-responsive" style="max-width:100%" src="{{$prod->image}}" />
</div>
<label>{{$prod->text}}</label>
@endforeach
@if(count($list) == 0)
		<label>暂无作品</label>
	@endif

