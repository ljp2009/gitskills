<div class="user_box">
    @if(count($list) > 0)
    <label>已经有{{count($list)}}人参与到了这个任务中。</label>
    <ul >
        @foreach($list as $partner)
        <li onclick="$.ymFunc.goTo('{{$partner->user->homeUrl}}')">
            <img class="ym_user_label_img am-circle" src="{{$partner->user->avatar->getPath(2,'128w_128h_1e_1c')}}" />
            <label class="ym_user_label_name">{{$partner->user->display_name}}</label>
        </li>
        @endforeach
    </ul>
    @else
    <p class="msg"> 还没有人参与到这个任务中。 </p>
    @endif
</div>
