<!-- 浏览历史记录 -->
@foreach($models as $model)
<div class="am-container ym_listitem" style="background-color:#fff;padding:0 1.2rem;border-bottom:solid 1px #e2e2e2">

	<div onclick="window.location='{{$model['url']}}'">
		<div style="font-size:1.4rem; color:#383838;height:2rem;overflow:hidden;margin-top:0.5rem;">
			{{$model['name']}}
		</div>
		<label style="font-size:1.2rem; color:#a5a5a5;margin-top:0.5rem; height:2rem;">
			{{$model['created_at']}}
		</label>
		
	</div>

</div>
@endforeach
