@if(count($taskList)>0)
@foreach($taskList as $task)
<div class="am-container ym-apply-item ym_listitem">
    <!--name row-->
      <div class="header-row">
        <div class="am-fl">
          <a href="{{$task->user->homeUrl}}"><img class="header-img am-circle" src="{{$task->user->avatar}}" ></a>
        </div>
        <div class="am-fl text-area">
          <div class="text-username">{{$task->user->display_name}}<label class="am-badge am-round {{$task->class}}">@if($task->status == 1)申请中@elseif($task->status == 2)参与中@endif</label></div>
          <div class="text-title">{{$task->task->title}}</div>
        </div>
      </div>
      <!--desc row-->
      <div class="intro-row">@if(mb_strlen($task->task->intro)<=50){{$task->task->intro}}@else{{mb_substr($task->task->intro,0,50).'...'}}@endif </div>
      <!--ope row-->
      <div class="operation-row">
        <div class="am-fl apply-time">申请时间：{{date('Y-m-d H:i',strtotime($task->created_at))}}</div>
<!--         <a type="button" class="am-btn am-btn-default am-btn-primary am-fr accept" @if($task->status == 2|| $task->status == 3|| $task->status == 4 || $task->status == 5)disabled="true"@endif data-status="{{$task->status}}" data-id="{{$task->id}}" data-type="{{$task->task->task_type}}" data-userID = "{{$task->user_id}}" onclick="acceptRequest(this,{{$task->status}},'{{$task->task->task_type}}',{{$task->id}},{{$task->user_id}});">同意</a> -->

      </div>
      <hr />
    </div>
@endforeach
@endif
