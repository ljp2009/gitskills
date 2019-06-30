<div class="ym_ip_liker_row">
<div class="ym_ip_liker_flag">
    <span> <i class="ymicon-heart"></i>{{$likeCount}} </span>
    <label>个用户喜欢</label>
</div>
	@foreach ($users as $user)
		<img src="{{$user->avatar->getPath(2,'64w_64h_1e_1c')}}"
		onclick="window.location='{{$user->homeUrl}}'" />
    @endforeach
</div>
