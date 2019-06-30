<!--第二轮投票-->
@extends('layouts.block')
@section('content')
@section('serverLoad')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css" />
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'参与投票'])
<div class="ym_multiplevotebox">
	<div class="am-container">
	@if(!$isDeleay)
	<form id="delivery">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<input type="hidden" name="task_id" value="{{ $task_id }}" />
		<div class="delivery-box">
			<ul class="am-avg-sm-3" style="">
				@if(count($vote)==0)
				@foreach($taskDelivery as $k=>$value)
				<li>
              		<img src="{{$value->delivery->image[0]->getPath(1,'100w_100h_4e_204-204-204bgc')}}" class="am-img-responsive am-thumbnail" alt="">
              		<span>喜欢</span>
              		<input type="checkbox" name="prefrence[]" value="{{$value->delivery_id}}" id="prefrence_{{$value->delivery_id}}" style="position:absolute;z-index:-1;visibility: hidden;" />
              	</li>
              	@endforeach
              	@else
              	@foreach($vote as $k=>$value)
				<li class="active" data-am-modal="{target: '#my-alert{{$value->id}}'}">
              		<img src="{{$value->delivery->image[0]->getPath(1,'100w_100h_4e_204-204-204bgc')}}" class="am-img-responsive am-thumbnail" alt="">
              		<span>喜欢</span>
              		<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert{{$value->id}}">
		                <div class="am-modal-dialog">
		                  <div class="am-modal-bd">
		                    <img src="{{$value->delivery->image[0]->getPath(1,'100w_100h_4e_204-204-204bgc')}}" style="width:100%" id="my-alert_img{{$value->id}}">
		                  </div>
		                </div>
		              </div>
              	</li>
              	@endforeach
              	@endif
			</ul>
		</div>
		@if(count($vote)==0)
		<div class="vote-intro">
			请选择你喜欢的作品（最多3个）
		</div>
		<div class="ym-confirm">
			<button class="am-btn am-btn-success" type="button">确定</button>
		</div>
		@else
		<div class="vote-intro">
			我喜欢的作品
		</div>
		@endif
		</form>
		@else
			<div class="vote-intro">
				该投票已过期!
			</div>
		@endif
	</div>
</div>
@show
	@parent
@section('runScript')
<script type="text/javascript">
// 	$('.delivery-box').find('li').on('click',function(){
// 		$(this).toggleClass('active');
// 		var len = $(this).parent().find('.active').length;
// 		if(len == 3){
// 			return false;
// 		}else if(len > 3){
// 			$(this).removeClass('active');
// 		}
		
// 	});
	@if(count($vote)==0)
	$('.delivery-box li').on('click',function(){
		$this = $(this);
		if(!$this.hasClass('active')){
			var length = $(this).parent().find('.active').length;
			if(length < 3){
				$this.addClass('active');
				$this.find('input').prop('checked',true);
			}
		}else{
			$this.removeClass('active');
			$this.find('input').prop('checked',false);
		}
	});
	
	$('.ym-confirm button').on('click',function(){
		$.ajax({
			type:'POST',
			url:'/task/multiplevote',
			data:$('#delivery').serialize(),
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					location.href='/reshall';
				}
				
			}
		});
	});
	@endif
	
</script>
@stop
	@stop
