<div class="ym-recomend">
	<div class="ym-hall-same-header">
	    <i class="ym-hall-same-icon ym-hall-icon-recomend ym-master-icon"></i><div class="ym-hall-same-title">达人推荐</div>
	</div>
        <ul class="ym-hall-recommand-master">
            @foreach($models as $master)
                <li>
                    <a href="{{$master->homeUrl}}">
                        <div class="avatar">
                            <img src="{{$master->avatar->getPath(2,'77h_77w_1e_1c')}}"
                                class="ym-hall-recommand-master" alt="">
                        </div>
                        <div class="label">{{$master->display_name}}</div>
                    </a>
                </li>
            @endforeach
        </ul>
        @if(count($models)==0)
        暂无
        @endif
        <div style="clear:both; height:1px"></div>
</div>

