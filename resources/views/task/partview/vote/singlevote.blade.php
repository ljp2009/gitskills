<!--第一轮投票-->
@extends('layouts.block')
@section('content')
@section('serverLoad')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css" />
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'参与投票'])
<div class="ym_votebox">
		<div class="am-container">
			@if(!$isDeleay)
				<div class="delivery-box">
					<img src="{{$taskDelivery->image[0]->getPath(1,'300w_300h_4e_90-155-213bgc')}}" class="am-img-responsive" alt="">
				</div>
				<div class="am-img-responsive vote-intro">
					你是否喜欢这个作品？
				</div>
				@if(empty($voteRecode))
				<div class="ym-islike">
					<div class="like" data-value="1">喜欢</div>
					<div class="dislike" data-value="0">不喜欢</div>
				</div>
				@else
				<div class="ym-islike">
					@if($voteRecode->is_like == 1)
					<div class="like" style="float:none;margin:0 auto;">喜欢</div>
					@else
					<div class="dislike" style="float:none;margin:0 auto;">不喜欢</div>
					@endif
				</div>
				@endif
			@else
				<div class="am-img-responsive vote-intro">
					该投票已过期!
				</div>
			@endif
		</div>
	</div>
@show
	@parent
@section('runScript')
<script type="text/javascript">
$('.ym-islike div').on('click',function(){
	var islike = $(this).attr('data-value')*1;
	if(!isNaN(islike)){
		$.ajax({
			type:'POST',
			url:'/task/singlevote',
			data:{id:{{$taskDelivery->id}},task_id:{{$taskDelivery->task_id}},islike:islike,_token:"{{ csrf_token() }}"},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					location.href='/reshall';
				}
				
			}
		});
	}
});
	
</script>
@stop
	@stop
