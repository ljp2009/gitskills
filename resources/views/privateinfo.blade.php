@extends('layouts.block')
@section('title',$name)
@section('content')
	@parent
	@section('serverload')
		@include('partview.detailheader',array('hideShare'=>true))
<div class="private-list-box" id="private">
	<div class="private-list">
        <div class="am-container private-letter">
	        <a href="/home/list/default/0/{{$model->send_id}}">
	          <div class="user-photo">
	            <img src="{{$model->fromUser->avatar}}" class="am-circle am-img-responsive" alt="">
	          </div>
          </a>
          <div class="private-info">
            <div class="user-name">
              {{$model->fromUser->display_name}}
              <span>{{$model->time}}</span>
            </div>
            <div class="private-msg" style="font-size:1.3rem;">
             {!!$model->msg!!}
            </div>
          </div>
        </div>
    </div>
</div>
@stop

	@section('runScript')
		<script type="text/javascript">

		</script>
	@show

@stop
