@if(count($taskList)>0)
@foreach($taskList as $task)
<div class="am-container ym-apply-item ym_listitem" onclick="location.href='/task/{{$task->id}}'">
    <!--name row-->
      <div class="header-row">
        <div class="am-fl">
          <a href="{{$task->user->homeUrl}}"><img class="header-img am-circle" src="{{$task->user->avatar}}" ></a>
        </div>
        <div class="am-fl text-area">
          <div class="text-username">{{$task->user->display_name}} <label class="am-badge am-round am-badge-danger" >{{$task->taskStepName}}</label></div>
          <div class="text-title">{{$task->title}}</div>
        </div>
      </div>
      <!--desc row-->
      <div class="intro-row">@if(mb_strlen($task->intro)<=25){{$task->intro}}@else{{mb_substr($task->intro,0,25).'...'}}@endif </div>
      <!--ope row-->
      <div class="operation-row">
      	<div class="am-fl">
			<label class="am-badge am-round" style="background-color:black">@if($task->is_crowdfunding === 1)众筹@else非众筹@endif</label>
		</div>
		<div class="am-fl">金币:{{$task->amount}}</div>
		<div class="am-fl">交付:@if($task->delivery_date != NULL){{str_replace('-','/',$task->delivery_date)}}@else暂无@endif</div>
        <a type="button" class="am-btn am-btn-default am-btn-primary am-fr accept" @if($task->step >= 3)disabled="true"@else href="/pubtask/edit/info/{{$task->id}}" @endif >发布</a>
        
      </div>
      <hr />
    </div>
@endforeach
@endif
