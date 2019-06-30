
<div class="ym-recomend" style="padding-top:0">
	<div class="ym-hall-list">
        @if(!is_null($items))
	    <div class="am-g">
	        <ul>
	        	@foreach($items as $k=>$item)
                <?php $obj = $item['obj']?>
    			<li>
					<a href="{{$obj->detailUrl}}">
			            <div class="ym-hall-recommand-box">
			            	<i class="ym-work-tag">{{$obj->typeLabel}}</i>
			                <img src="{{$obj->cover->getPath(1, '186w_220h_1e_1c')}}" class="ym-hall-recommand-cover-img" alt="">
			            </div>
			            <div class="ym-hall-recommand-name">{{$obj->name}}</div>
			            <div class="ym-hall-recommand-intro">{{$obj->intro}}</div>
		            </a>
		  		</li>
				@endforeach
			</ul>
	    </div>
        @endif
	</div>
</div>
