<div class="ym-recomend">
	<div class="ym-hall-same-header">
	    <i class="ym-hall-same-icon ym-hall-icon-recomend ym-activity-icon"></i><div class="ym-hall-same-title">活动进行中</div>
	    <div class="am-fr ym-hall-more" onclick="$.ymFunc.goTo('/activity/list')"> 更多活动 </div>
	</div>
	<div class="ym-hall-list">
		<div class="am-g">
			<table class="ym-activity-box">
				<tr>
					<td class="ym-activity-cover" rowspan="4">
						<img src="{{$model->cover->getPath(1,'150w_150h_1e_1c')}}" class="ym-hall-recommand-cover-img" alt="">
					</td>
					<td class="ym-activity-info ym-activity-top">
						<div class="ym-activity-name">{{$model->title}}</div>
					</td>
				</tr>
				<tr>
					<td class="ym-activity-info">
						<div class="ym-activity-date">{{$model->from_date}}~{{$model->to_date}}</div>
					</td>
				</tr>
				<tr>
					<td class="ym-activity-info">
						<div class="ym-activity-intro">
							&nbsp;
						</div>
					</td>
				</tr>
				<tr>
					<td class="ym-activity-info ym-activity-bottom">
                        @if($model->id == 0)
						<div class="ym-activity-join" style="background-color:silver">
							<a href="javascript:void(0)">敬请期待</a>
						</div>
                        @else
                            @if($model->from_date > date('Y-m-d H:i:s'))
						<div class="ym-activity-join">
							<a href="#">即将开始</a>
						</div>
                            @elseif($model->to_date < date('Y-m-d H:i:s'))
                                                <div class="ym-activity-join">
							<a href="{{$model->detailUrl}}">已经结束</a>
						</div>
                            @else
                                                <div class="ym-activity-join">
							<a href="{{$model->detailUrl}}">立即参加</a>
						</div>
                            @endif
                        @endif
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
