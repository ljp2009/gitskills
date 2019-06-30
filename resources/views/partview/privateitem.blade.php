@foreach($models as $val)
<div class="private-list ym_listitem">
    <div class="private-letter" data-read="{{$val->status}}" data-id ="{{$val->id}}" data-href="/private/list/dialog/0/{{$val->to_user_id}}" onclick="updateLetterStatus(this)">
        <div class="user-photo">
            <img src="{{$val->avatar->getPath(2,'48w_48h_1e_1c')}}"  alt="">
        </div>
        <div class="user-name">
          {{$val->display_name}}
        </div>
        <div class="created-time">
          {{$val->time}}
        </div>
        <div class="letter-content">
         <span class="@if($val->status == 'N') active @endif"></span>
         {!!$val->msg!!}
        </div>
    </div>
</div>
@endforeach
