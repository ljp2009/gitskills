@foreach($models as $value)
<div class = "am-container ym_dresser ym-line-list-item">
<?php
    $class = '';
    if ($value->status == 0) {
        $class = 'reject';
    }
    if ($value->status == 2) {
        $class = 'agree';
    }
?>
<?php $uCredit = $value->user->getUserCredit(); ?>
    <div class="ym_credit_flag level{{$uCredit['id']}}">{{$uCredit['label']}}</div>
    <i class="tag {{$class}}">{{$value->statusName}}</i>	
 	<div class = "am-fl" style="width:30%;">
		<a href="{{$value->user->homeUrl}}">
			<img style="max-height:80px;max-width:80px" class=" am-circle my_dresser_photo" src="{{$value->user->avatar->getPath(2,'80w_80h_1e_1c')}}" alt="" >
		</a>
	</div>
	<div class="am-fl am-text-middle" style="width:65%;margin-left:1rem">
		<span class="name">{{$value->user->display_name}}</span>
		@if($value->user->sex == '男')
			<i class="ym-icon-female"></i>
		@elseif($value->user->sex == '女')
			<i class="ym-icon-male"></i>
		@endif

		<br>
		<div style="min-height: 3rem;">

			@if(count($value->user->getAttrSkill)>0)
				@foreach($value->user->getAttrSkill as $k => $skill )
				 	@if($k > 0 && $k%4== 0)
						<br>
					@endif
					<span class="am-badge am-badge-secondary am-radius icon" style="font-size:10px;padding:3px 6px 3px 6px;margin-right:5px; display:inline-block;background:#ef7c1e; font-weight:100;">
						{{$skill->skillName}}
					</span>
				@endforeach

			@endif

		</div>
		<button relid="{{$pid}}" userid="{{$value->user->id}}" username="{{$value->user->display_name}}" class="am-btn am-btn-secondary am-radius"
                style="padding:0.3rem 1.5rem;font-size:1.2rem;font-weight:bold;" onclick="operation(this,'communicate')" >沟通</button>
        @if($value->status == App\Common\TaskPartnerStatus::REQUEST)
        <button relid="{{$pid}}" userid="{{$value->user->id}}" username="{{$value->user->display_name}}" class="am-btn am-btn-success am-radius"
        		style="padding:0.3rem 1.5rem;font-size:1.2rem;font-weight:bold;" onclick="operation(this,'agree')" >同意</button>
        <button relid="{{$pid}}" userid="{{$value->user->id}}" username="{{$value->user->display_name}}" class="am-btn am-btn-danger am-radius"
        		style="padding:0.3rem 1.5rem;font-size:1.2rem;font-weight:bold;color:#fff;" onclick="operation(this,'reject')" >拒绝</button>
        @elseif($value->status != App\Common\TaskPartnerStatus::PARTNER)
        <button relid="{{$pid}}" userid="{{$value->user->id}}" username="{{$value->user->display_name}}" class="am-btn am-btn-danger am-radius"
        		style="padding:0.3rem 1.5rem;font-size:1.2rem;font-weight:bold;" onclick="operation(this,'undo')" >撤销</button>
        @endif

	</div>
</div>
@endforeach
