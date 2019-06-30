@if(count($models) > 0)
@foreach($models as $key => $value)
	<div class="am-cf am-border"></div>
	<div class="am-container dimension-list-box ym_listitem" >
	      <div class="ym_cover"  onclick="window.location='/dimpub/list/diminfo/0/{{ $value->id }}'">
	        <img src="{{$value->header->getPath(1,'150w_160h_1e_1c')}}"  alt="" class="am-img-responsive">
	      </div>
	      <div class="ym_info" >
	        <h4>{{$value->name}}</h4>
	        <span>入驻：{{$value->enterSumValue}} &nbsp;&nbsp;领主：{{$value->user->display_name}}</span>
	        <p>{{$value->text}}</p>
	        <div class ="dimension-operation">
	        	<div class="am-radius" style="margin-right:0.8rem;">
	        		<a href="/dimpub/list/diminfo/0/{{ $value->id }}">去看看</a><i class="ymicon-right" style="margin-left:0.5rem"></i>
	        	</div>
				@if($value->isEnter == 'N')
                <div id="btn_dim_{{$value->id}}" class="am-radius"
                    onclick="enterDimension({{$value->id}},listAfterEnter)">
                    <i class="ymicon-join" style="margin-right:0.5rem"></i><a href="javascript:void(0)">入驻次元</a>
                </div>
				@elseif($value->isEnter == 'Y')
                <div id="btn_dim_{{$value->id}}" class="am-radius dimension-operation-disable" onclick="enterDimension({{$value->id}},listAfterEnter)">
                    <i class="ymicon-join" style="margin-right:0.5rem"></i><a href="javascript:void(0)">已入驻</a>
                </div>
				@elseif($value->isEnter == 'owner')
                <div class="am-radius" onclick="$.ymFunc.goTo('/dimension/edit/{{$value->id}}')">
                    <i class="ymicon-join" style="margin-right:0.5rem"></i><a  href="javascript:void(0)">编辑次元</a>
                </div>
				@elseif($value->isEnter == 'activity')
                <div class="am-radius">
                    <a  href="javascript:void(0)">活动次元</a>
                </div>
                @else
                <div class="am-radius" onclick="$.ymFunc.goTo('/auth/login')">
                    <i class="ymicon-join" style="margin-right:0.5rem"></i><a  href="javascript:void(0)">未登录</a>
                </div>
                @endif
	        </div>

	      </div>

    </div>
@endforeach
@endif
