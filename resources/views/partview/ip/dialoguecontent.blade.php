<!--经典台词内容-->	
 @foreach ($dialogues as $dlg)
  <div class="am-g ym-r-header" name="oneitem">
	<div class="am-u-sm-2 am-u-md-1">
		<div class="am-fl ym-obj-role">
		  <img class="am-circle" src="/imgs/headers/hd1.jpg" />
		  <label>{{$dlg->user->display_name}}</label>
	  </div>
	</div>
	<div class="am-u-sm-9 am-u-md-10 ym-f-12 ym-c-grey" >
		{{$dlg->text}}
	</div>
	<div class="am-u-sm-1 am-u-md-1">
		@include('common.like', array('likeObj'=>array('resourceName'=>'ip_dialogue', 'resourceId'=>$dlg->id, 'isLike'=>$dlg->iLike )))
	</div>
  </div>
  @endforeach