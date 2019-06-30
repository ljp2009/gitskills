@foreach($data_list as $one)
<li class="ym_cm_listitem">
    <div id="act_bg">
        <div id="act_pic">
            <a href="{{$one->detailUrl}}"><img src="{{$one->cover->getpath(1,'410w_190h_1e_1c')}}" class="attachment-thumbnail wp-post-image" alt="fdasfds" /></a>
        </div>

        <div class="listtitle">{{$one['title']}}</div>
        <div class="listtag">参与人数：{{$one['count']}}</div>
        @if($one['leave_days'] <= 0)
        <div class="listdate">活动已结束</div>
        @elseif($one['from_date'] >= date('Y-m-d H:i:s'))
        <div class="listdate">活动未开始</div>
        @else
        <div class="listdate">剩余时间：{{$one['leave_days']}}天</div>
        @endif
    </div>
</li>
@endforeach
