@if(is_array($models))
@foreach($models as $k=>$val)
	@if($val['type'] == 'img')
	<div align = "center">
		<img style="max-width:100%" src="{{$val['src']}}">
	</div>
		@if(isset($val['desc']) && !empty($val['desc']))
		<div align = "center">
			<p>{{$val['desc']}}</p>
		</div>
		@endif
	@elseif($val['type'] == 'text')
	<div>
		<p>{{$val['text']}}</p>
	</div>
	@elseif($val['type'] == 'link')
	<div>
		<a href="{{$val['link']}}">{{$val['name']}}</a>
	</div>
	@endif
@endforeach
@else
	<div align = "center">
		<img style="max-width:100%" src="">
	</div>
	<div>
		<p>{{$models}}</p>
	</div>
@endif