
  @foreach ($scenes as $scene)
<!--经典场景内容-->	
  <div class="am-g ym-r-header"  name="oneitem">
	<div class="am-u-sm-2 am-u-md-1">
		<div class="am-fl ym-obj-role">
		  <img class="am-circle" src="/imgs/headers/hd2.jpg" />
		  <label>{{$scene->user->display_name}}</label>
	  </div>
	</div>
	<div class="am-u-sm-9 am-u-md-10 ym-f-12 ym-c-grey">
	  <div class="am-g">
	  	@if(sizeof($scene->pics)>0)
		<div class="am-u-sm-6 am-u-mi-5 am-u-md-2">
		@foreach ($scene->pics as $pic)
		  <img class="am-img-responsive" src="{{ $pic }}" />
		@endforeach
		</div>
		@endif
		<div class="am-u-sm-6 am-u-mi-7 am-u-md-10" style="padding-left:0">
		  {{ $scene->text }}
		</div>
	  </div>
	</div>
	<div class="am-u-sm-1 am-u-md-1">
		@include('common.like', array('likeObj'=>array('resourceName'=>'ip_scene', 'resourceId'=>$scene->id, 'isLike'=>$scene->iLike )))
	</div>
  </div>
  @endforeach