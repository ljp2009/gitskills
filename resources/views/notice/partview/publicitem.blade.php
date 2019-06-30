@foreach($data as $val)
<div class="ym_cm_card ym_notice_public_item ym_listitem" msgId="{{$val->id}}">
    <pre>{!!$val->text!!}</pre>
    <label class="chat-time">{{$val->created_at}}</label>
</div>
@endforeach
