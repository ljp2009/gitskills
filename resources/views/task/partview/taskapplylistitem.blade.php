@if(count($taskList)>0)
@foreach($taskList as $taskParter)
<div class="am-container ym-apply-item ym_listitem" id='id_{{$taskParter->id}}'>
    <!--name row-->
      <div class="header-row">
        <div class="am-fl">
          <a href="{{$taskParter->user->homeUrl}}"><img class="header-img am-circle" src="{{$taskParter->user->avatar}}" ></a>
        </div>
        <div class="am-fl text-area">
          <div class="text-username">{{$taskParter->user->display_name}}
            
          </div>
          <div class="text-status"><label class="am-badge am-round {{$taskParter->class}}">@if($taskParter->status == 1)申请中@elseif($taskParter->status == 2)参与中@endif</label></div>

        </div>
      </div>
      <!--desc row-->
      <div class="intro-row">{{$taskParter->request_description}}</div>
      <!--ope row-->
      <div class="operation-row">
        <div class="am-fl apply-time">申请时间：{{date('Y-m-d H:i',strtotime($taskParter->created_at))}}</div>
       @foreach($taskParter->btns as $btnName => $btnType)
        @if($btnName == 'agree')
            <a type="button" style="margin-left:1rem" class="am-btn am-radius am-btn-primary am-fr"
                onclick="operationRequest('{{$taskParter->id}}','agree')"
            >同意</a>
        @elseif($btnName == 'reject')
            <a type="button" style="margin-left:1rem" class="am-btn am-radius am-btn-danger am-fr"
                {{$btnType?'':'disabled="true"'}}
                onclick="operationRequest('{{$taskParter->id}}','reject')"
            >{{$btnType?'拒绝':'已拒绝'}}</a>
        @elseif($btnName == 'join')
            <a type="button" style="margin-left:1rem" class="am-btn am-radius am-btn-primary am-fr"
                {{$btnType?'':'disabled="true"'}}
            >{{$btnType?'':'参与中'}}</a>
        @elseif($btnName == 'confirm')
            <a type="button" style="margin-left:1rem" class="am-btn am-radius am-btn-primary am-fr"
                {{$btnType?'':'disabled="true"'}}
                onclick="operationRequest('{{$taskParter->id}}','confirm')"
             >{{$btnType?'确认合作':'合作中'}}</a>
        @elseif($btnName == 'giveup')
            <a type="button" style="margin-left:1rem" class="am-btn am-radius am-btn-danger am-fr"
                {{$btnType?'':'disabled="true"'}}
                onclick="operationRequest('{{$taskParter->id}}','giveup')"
             >{{$btnType?'舍弃':'已舍弃'}}</a>
        @endif
       @endforeach

      </div>
      <hr />
    </div>
@endforeach
@endif
