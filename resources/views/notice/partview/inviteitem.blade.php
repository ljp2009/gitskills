@for($i=0; $i<count($models); $i++)
<?php $val = $models[$i]; ?>
<div class="chat-list ym_listitem" msgId="{{$val->id}}">
    <div class="user-photo {{$val->isOwner?'right':''}}">
        <img src="{{$val->avatar->getPath(2,'48w_48h_1e_1c')}}"  alt="">
    </div>
    <div class="chat-content {{$val->isOwner?'right':''}}" >
        <p style="white-space:inherit;" >{!!$val->formatMsg!!}</p>
        <span class="chat-time">{{$val->time}}</span>
    </div>
</div>
@endfor
