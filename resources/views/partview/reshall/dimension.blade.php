<div class="ym-recomend">
	<div class="ym-hall-same-header">
	    <i class="ym-hall-same-icon ym-hall-icon-recomend ym-dimension-icon"></i><div class="ym-hall-same-title">次元</div>
	    <div class="am-fr ym-hall-more" onclick="toMoreDimension()">
	          更多次元
	    </div>
	    <script type="text/javascript">
		    function toMoreDimension(){
		        window.location = '/dimension/list/default/0/0';
		    }
		</script>
	</div>
	<div class="ym-special-list ym-dimension-list">
	    <div class="am-g">
	    	<ul>
            @foreach($models as $model)
	    		<li>
	    			<a href="{{$model->dimensionPath}}">
			            <div class="ym-hall-recommand-box">
			                <img src="{{$model->header->getPath(1,'186w_186h_1e_1c')}}" class="ym-hall-recommand-cover-img" alt="">
			            </div>
			            <div class="ym-hall-recommand-name">{{ $model->name }}</div>
			            <div class="ym-hall-recommand-intro">入驻：{{$model->enterSumValue}}</div>
		            </a>
	    		</li>
            @endforeach
	    	</ul>
	    </div>
	</div>
</div>

