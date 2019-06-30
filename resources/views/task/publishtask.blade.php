<!--发布任务-->
@extends('layouts.publish')
@section('content')
	@section('serverLoad')
	<link rel="stylesheet" type="text/css" href="/css/ym_task.css" />
	@include('partview.detailheader')
	<div class="am-container ym-task-item">
	<!--name row-->

	<div class="header-row">
		<div class="am-fl">
			<img class="header-img am-circle" src="{{$task->user->avatar}}" >
		</div>
		<div class="am-fl text-area">
			<div class="text-title">{{$task->title}}</div>
			<div class="text-username">{{$task->user->display_name}}创建于{{$task->createdDate}}</div>
		</div>
	</div>
	<div class="tags-row">
		<label class="am-badge am-round am-badge-success" >{{$task->taskTypeName}}</label>
		<label class="am-badge am-round am-badge-danger" >{{$task->taskStepName}}</label>
		@if ($task->is_crowdfunding)
			<label class="am-badge am-round am-badge-danger" >众筹中</label>
		@endif
		@if(!empty($task->tagItems))
		@foreach ($task->tagItems as $tag)
			<label class="am-badge am-round am-badge-warning" >{{$tag}}</label>
		@endforeach
		@endif
	</div>
	@if(!is_null($task->deliveryDateShow))
	<div class="am-progress am-progress-striped" style="margin-top:0.5rem">
		<div class="am-progress-bar am-progress-bar-success " style="width: {{$task->runPercent}}%">
		@if ($task->runPercent >=50)
			{{$task->deliveryDateShow}}
		@endif
		</div>
		<label style="font-size:1rem;margin-left:0.5rem">
		@if ($task->runPercent <50)
			{{$task->deliveryDateShow}}
		@endif
		</label>
	</div>
	@endif
	<div class="coin-row">
		<div class="am-g">
			<div class="am-u-sm-12 am-u-mi-5 am-u-md-6">
				<label>{{$task->amountValue}}</label>
			</div>
		</div>
	</div>
	<hr />
<div class="am-tabs" data-am-tabs="{noSwip:1}" id="content_tab">
	<ul class="am-tabs-nav am-nav am-nav-tabs">
		<li class="am-active"><a id="tabDesc" href="#tab1">说明</a></li>
	</ul>
	<!--desc row-->
	<div class="am-tabs-bd">
		<div class="am-tab-panel am-fade am-in am-active" id='tab1' >
			<pre>{{ $task->intro }}</pre>
			@foreach ($task->imagePaths as $img)
				<img class="am-img-responsive am-img-thumbnail" src="{{$img}}" />
			@endforeach
		</div>
	</div>
</div>
<?php 
    $oneform = Publish::form('/task/publish', '');
//     $oneform->addComp(array('name'=>'is_recomend', 'type'=> Publish::$TP_CHECK, 'label'=>'是否推荐', 'selectables'=>array('recomend')));
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $id));

?>
<div class="am-form-group">
	<label class="am-checkbox-inline" id="is_recomend">
	     <input type="checkbox" name="is_recomend" value="recomend" class="ym-publish-field" validate="required">是否推荐
	</label>
</div>

<?php 
    $oneform->addComp(array('name' => 'rules', 'type' => Publish::$TP_RULE, 'label' => '筛选规则', 'jsonUrl' => '/json/taskRule'));
?>
<div class="am-form-group am-tenders">
	<label for="task_recomend_num">推荐人数</label>
	<input type="number" name="task_recomend_num" min="1" max="100" placeholder="推荐人数" class="am-form-field ym-publish-field" value="1" validate="required" id="task_recomend_num" />
</div>
<?php 
    $oneform->end('发布');
?>

	@show
	@parent
	@section('runScript')
	<script type="text/javascript" src="/js/ym_rule.js"></script>
	<script type="text/javascript">
		
		if($('input[name="is_recomend"]').prop('checked')){
			$('.am-tenders').css('display','');
		}else{
			$('.am-tenders').css('display','none');
		}
		$('#is_recomend').click(function(){
			var check = $(this).find('input[name="is_recomend"]').prop('checked');
			if(check){
				$('.am-tenders').css('display','');
			}else{
				$('.am-tenders').css('display','none');
			}
		});
		
	</script>
	@show
@stop