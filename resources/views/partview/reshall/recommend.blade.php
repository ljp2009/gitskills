<div class="ym-recomend">
	<div class="ym-hall-same-header">
	    <i class="ym-hall-same-icon ym-hall-icon-recomend"></i><div class="ym-hall-same-title">有妹推荐</div>
	    <div class="am-fr ym-hall-more" onclick="$.ymFunc.goTo('/reshall/list/recommend/0')"> 更多推荐 </div>
	</div>
	<div class="ym-hall-list">
        @if(!is_null($model))
	    <div class="am-g">
	        <ul>
	        	@foreach($model->recommends as $k=>$val)
    			<li>
					<a href="{{$val->url}}">
			            <div class="ym-hall-recommand-box">
			            	<i class="ym-work-tag">{{$val->tag}}</i>
			                <img src="{{$val->image->getPath(1, '186w_220h_1e_1c')}}" class="ym-hall-recommand-cover-img" alt="">
			            </div>
			            <div class="ym-hall-recommand-name">{{$val->name}}</div>
			            <div class="ym-hall-recommand-intro">{{$val->intro}}</div>
		            </a>
		  		</li>
				@endforeach
			</ul>
	    </div>
        @endif
	</div>
</div>
