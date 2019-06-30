

@foreach($models as $value)
<div class = "am-container ym_dresser ym-line-list-item ym_listitem" onclick="window.location='{{$value->homeUrl}}'" >
<?php $uCredit = $value->getUserCredit(); ?>
    <div class="ym_credit_flag level{{$uCredit['id']}}">{{$uCredit['label']}}</div>
 	<div class = "am-fl ym_dresser_left" >
		<a href="{{$value->homeUrl}}">
			<img class=" am-circle my_dresser_photo" src="{{$value->avatar->getPath(2,'80w_80h_1e_1c')}}" alt="" >
		</a>
	</div>
	<div class="am-fl am-text-middle ym_dresser_right">
		<span class="name">{{$value->display_name}}</span>
		<br>
		<div>
			<span class="label">关注</span>
			<span class="number">{{$value->followNum}}</span>
			<span class="label">粉丝</span>
			<span class="number">{{$value->fansNum}}</span>
		</div>
		<br>
		<div>
			@if(count($value->getAttrSkill)>0)
				@foreach($value->getAttrSkill as $k => $skill )
				 	@if($k > 0 && $k%4== 0)
						<br>
					@endif
					<span class="am-badge am-badge-secondary am-radius icon">{{$skill->skillName}}</span>
				@endforeach
			@endif
		</div>
		

	</div>
</div>
@endforeach
