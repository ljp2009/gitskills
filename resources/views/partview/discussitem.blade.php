@foreach($items as $item)
<div class="ym_comment_box discuss_flag_{{$item->id}}" >
        <img class="ym_comment_box_avatar" src={{$item->user->avatar->getPath(2,'80w_80h_1e_1c')}} onclick="$.ymFunc.goTo('{{$item->user->homeUrl}}')" />
        <label class="ym_comment_box_title">{{$item->user->display_name}}</label>
        <label class="ym_comment_box_time">发布于 {{$item->created_at}}</label>
        <div class="ym_comment_box_text">{{$item->text}}</div>
        @if($item->isLike)
        <i discid="{{$item->id}}" class="ymicon-heart ym_comment_box_like"
                onclick="$.ymFunc.switchLike('discussion',{{$item->id}}, switchLikeIcon)"></i>
        @else
        <i discid="{{$item->id}}" class="ymicon-heart-o ym_comment_box_like"
                onclick="$.ymFunc.switchLike('discussion',{{$item->id}}, switchLikeIcon)"></i>
        @endif
        @if(auth::check() && ($item->user_id == auth::id() || auth::user()->role == 'admin'))
        <i discid="{{$item->id}}" class="ymicon-delete ym_comment_box_menu"
                onclick="deleteDiscuss({{$item->id}})"></i>
        @endif
        <span discid="{{$item->id}}" class="ym_comment_box_like_count">{{$item->like_sum}}</span>
</div>
@endforeach
