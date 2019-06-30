@if($model->type == 11)
@foreach($acts_ranking as $one_join)
@if(!empty($one_join['image']))
<li class="ym_cm_listitem">
    <div class="act_bg">
        <div class="act_border">
            <div clsss="act_pic" >
                <a href="{{$one_join['detailUrl']}}"><img src="{{$one_join['image']}}" class="act_pic_img" alt="fdasfds" /></a>
                <div style="float:left;margin-top:10px;margin-bottom:15px;color:#383838;font-size:1.4rem;width:100%;">{{$one_join['name']}}</div>
            </div>
            <div style="width:100%;height:40px;margin-top:10px;clear: left;">
                <div style="width:50px;float:left"><a href="{{$one_join['homeurl']}}"><img src="{{$one_join['avatar']}}" class="act_per_img" alt="fdasfds" /></a></div>
                <div style="width:100px;float:left">
                    <p class="act_list_name">{{$one_join['display_name']}}</p>
                    <div class="public_time">发布于 {{$one_join->created_at->diffForHumans()}}</div>
                </div>
                <div class="likes_num"><span style="line-height:5px;">{{$one_join['discussion']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="ymicon-comment act_per_icon"></i></div>
                <div class="pinglun"><span style="line-height:5px;">{{$one_join['like_sum']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="<?php echo $one_join['is_like'] ? 'ymicon-heart' : 'ymicon-heart-o'?> act_per_icon"></i></div>
            </div>
        </div>
    </div>
</li>
@else
<li class="ym_cm_listitem">
    <div class="act_bg">
        <div class="act_border">
            <div clsss="act_pic">
                <a href="{{$one_join['detailUrl']}}">
                <div style="margin-top:10px;margin-bottom:10px;color:#383838;font-size:1.4rem;">{{$one_join['name']}}</div><br>
                <p style="margin:0 0 0 0;color:#929292;font-size:1.3rem;margin-bottom:15px;margin-top: -16px;line-height:17px;">
                    {{str_limit($one_join['intro'],'140','...')}}
                </p>
                </a>
            </div>
            <div style="width:100%;height:40px;margin-top:10px;">
                <div style="width:50px;float:left"><a href="{{$one_join['homeurl']}}"><img src="{{$one_join['avatar']}}" class="act_per_img" alt="fdasfds" /></a></div>
                <div style="width:100px;float:left">
                    <p class="act_list_name">{{$one_join['display_name']}}</p>
                    <div class="public_time">发布于 {{$one_join->created_at->diffForHumans()}}</div>
                </div>
                <div class="likes_num"><span style="line-height:5px;">{{$one_join['discussion']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="ymicon-comment act_per_icon"></i></div>
                <div class="pinglun"><span style="line-height:5px;">{{$one_join['like_sum']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="<?php echo $one_join['is_like'] ? 'ymicon-heart' : 'ymicon-heart-o'?> act_per_icon"></i></div>
            </div>
        </div>
    </div>
</li>
@endif
@endforeach
@elseif($model->type == 12)
@foreach($acts_ranking as $one_join)
@if(!empty($one_join['image']))
<li class="ym_cm_listitem">
    <div class="act_bg">
        <div class="act_border">
            <div clsss="act_pic">
                <a href="{{$one_join['detailUrl']}}"><img src="{{$one_join['image']}}" class="act_pic_img" alt="fdasfds" /></a>
            </div>
            <div style="width:100%;height:40px;margin-top:10px;">
                <div style="width:50px;float:left"><a href="{{$one_join['homeurl']}}"><img src="{{$one_join['avatar']}}" class="act_per_img" alt="fdasfds" /></a></div>
                <div style="width:100px;float:left">
                    <p class="act_list_name">{{$one_join['display_name']}}</p>
                    <div class="public_time">发布于 {{$one_join->created_at->diffForHumans()}}</div>
                </div>
                <div class="likes_num"><span style="line-height:5px;">{{$one_join['discussion']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="ymicon-comment act_per_icon"></i></div>
                <div class="pinglun"><span style="line-height:5px;">{{$one_join['like_sum']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="<?php echo $one_join['is_like'] ? 'ymicon-heart' : 'ymicon-heart-o'?> act_per_icon"></i></div>
            </div>
        </div>
    </div>
</li>
@else
<li class="ym_cm_listitem">
    <div class="act_bg">
        <div class="act_border">
            <div clsss="act_pic">
                <a href="{{$one_join['detailUrl']}}">
                    <p style="margin:0 auto;color:#929292;font-size:1.3rem;margin-bottom:15px;">
                        {{str_limit($one_join['text'],'140','...')}}
                    </p>
                </a>
            </div>
            <div style="width:100%;height:40px;margin-top:10px;">
                <div style="width:50px;float:left"><a href="{{$one_join['homeurl']}}"><img src="{{$one_join['avatar']}}" class="act_per_img" alt="fdasfds" /></a></div>
                <div style="width:100px;float:left">
                    <p class="act_list_name">{{$one_join['display_name']}}</p>
                    <div class="public_time">发布于 {{$one_join->created_at->diffForHumans()}}</div>
                </div>
                <div class="likes_num"><span style="line-height:5px;">{{$one_join['discussion']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="ymicon-comment act_per_icon"></i></div>
                <div class="pinglun"><span style="line-height:5px;">{{$one_join['like_sum']}}</span></div>
                <div class="likes" onclick="location_url('{{$one_join['detailUrl']}}')"><i class="<?php echo $one_join['is_like'] ? 'ymicon-heart' : 'ymicon-heart-o'?> act_per_icon"></i></div>
            </div>
        </div>
    </div>
</li>
@endif
@endforeach
@endif
