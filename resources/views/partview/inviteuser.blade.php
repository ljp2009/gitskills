@foreach($model as $userinfo)
<div class="am-container" style="margin-top:1rem; padding-bottom:1rem; border-bottom:solid 3px #eeeeee">
	<div class="am-fl">
		<img class="am-circle" src="http://s.amazeui.org/media/i/demos/bw-2014-06-19.jpg?" 
				width="80" height="80"/>
	</div>
	<div class="am-fl am-text-middle" style="margin-left:1rem;padding-top:1rem">
		<span style="margin-left:0.5rem"> {{$userinfo->name}}</span>
		<br>
		@foreach($userinfo->skill as $key=>$skill)
		<span class="am-badge am-badge-secondary am-round" style="margin-left:0.5rem"> {{$skill}}</span>
		@endforeach
	</div>
</div>
@endforeach