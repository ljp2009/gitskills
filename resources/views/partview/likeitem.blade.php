@if(count($models)>0)
	@foreach($models as $year=>$yearItems)
		<div class="my-favorite-box">
			<div class="time-year">
				{{$year}}
			</div>
			@foreach($yearItems as $day=>$dayItems)
            @foreach($dayItems as $item)
			<div class="am-favorite-info ym_listitem">
				<div class="time-point"></div>
				<span class="circle-squre"></span>
				<div class="time-line"> {{$day}}</div>
				<div class="ym-home-ip-title" onclick="window.location='/ip/{{$item['ip']->id}}'">
					{{$item['ip']->name}}
				</div>
				<div class="my-favorite-img" onclick="window.location=/ip/{{$item['ip']->id}}" style="position:relative;z-index:1;">
                    <a href="/ip/{{$item['ip']->id}}" style="display:block;"><img src="{{$item['ip']->cover->getPath(1,'122h_87w_1e_1c')}}" /></a>
				</div>
                <div class="ym-favorite-detail" style="z-index:0;">
                    <div class="my-score-box">
                      <span class="ym-favorite-detail-label">评分:</span>
                    <div class="my-score-star">
                      @for($i=1; $i<=5; $i++)
                        @if(($item['ip']->averageScore-$i) >= 0)
                            <i class="ymicon-star ym_active"></i>
                        @else
                            <i class="ymicon-star"></i>
                        @endif
                      @endfor
                      </div>
                    </div>
                    <div class="my-score-box">
                        <span class="ym-favorite-detail-label">收获:</span>
                        <div class="ym-home-ip-gold">
                        {{$item['goldRecord']}}金币
                        </div>
                    </div>
                    <div class="my-score-box">
                        <span class="ym-favorite-detail-label">同好:</span>
                        <br />
                        <div class="ym-home-ip-samelike-imgbox">
                        @if(count($item['sameLikes']) > 0)
                        <a href="/user/list/samelikelist/0/{{$item['ip']->id}}">
                            @foreach($item['sameLikes'] as $user)
                                <img src="{{$user->avatar->getPath(2,'28h_28w_1e_1c')}}" alt="" class="ym-home-ip-samelike-img am-circle">
                            @endforeach
                        </a>
                        @endif
                        </div>
                    </div>
                </div>
				<div class="am-cf"></div>
				<p>
                    {{$item['ip']->intro->getShotIntro(40)}}
				</p>
				<div class="am-g">
					@if(count($item['ipScenes'])>0)
					@foreach($item['ipScenes'] as $scene)
                    <div class="am-u-sm-4" style="padding:2px">
					<span><img src="{{$scene->firstImage->getPath(1,'164w_126h_1e_1c')}}" class = "am-img-responsive" alt=""></span>
                    </div>
					@endforeach
					@endif
				</div>
			</div>
            @endforeach
			@endforeach
		</div>
@endforeach
@endif
