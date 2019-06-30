<!-- 技能 -->
@foreach($models as $key=>$value)
<div class="am-container ym-skill-box ym_listitem">
		<h4>技能{{$value['name']}}
			@if(Auth::check()&& Auth::user()->id == $value->role->creator)
  			<a href="/roleskill/edit/{{$value->id}}" class="am-fr">
			  <i class="am-icon-edit"></i>
		  </a>
		  @endif
		</h4>
		<div class="ym-skill-info">
			<div class="skill-img-box">
				<img src="{{$value->header}}" alt="" class="am-img-responsive">
				<span style="@if(mb_strlen($value['name'])>4) font-size:1.4rem; @endif">{{$value['name']}}</span>
			</div>
			<div class="ym-skill-type">
				<span>{{$value->getIpInfo->name}}</span>
				<p>{{$value['intro']}}</p>
			</div>
		</div>
	</div>
	<hr class="ym-border-hr">
@endforeach