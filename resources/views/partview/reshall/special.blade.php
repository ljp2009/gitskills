<div class="ym-recomend">
	<div class="ym-hall-same-header">
	    <i class="ym-hall-same-icon ym-hall-icon-recomend ym-special-icon"></i><div class="ym-hall-same-title">精选专辑</div>
	    <div class="am-fr ym-hall-more" onclick="$.ymFunc.goTo('/special/list/default/0')">
	          更多专辑
	    </div>
	</div>
	<div class="ym-special-list">
	    <div class="am-g">
	    	<ul>
            @foreach($models as $special)
	    		<li class="ym_special_item">
	    			<a href="/special/detail-{{$special->id}}/0">
			            <div class="ym-hall-recommand-box">
			                <img src="{{$special->img->getPath(1,'290w_180h_1e_1c')}}" class="ym-hall-recommand-cover-img" />
			            </div>
			            <div class="ym-hall-recommand-name">{{$special->name}}</div>
			            <div class="ym-hall-recommand-intro">{{$special->intro}}</div>
		            </a>
	    		</li>
            @endforeach
	    	</ul>
	    </div>
	</div>

</div>
