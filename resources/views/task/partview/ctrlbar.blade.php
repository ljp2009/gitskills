<!--操作栏-->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default ym-navbar-default" id="navbar">
    <ul id="ctrlBar" taskId='{{$taskId}}' class=" am-navbar-nav am-cf am-avg-sm-{{count($actions)}} ">
    @foreach($actions as $act)
    <li>
        @if($act == 'back')
        <a href="javascript:void(0)" action="back">
            <span class="ymicon-t-back"></span><span class="am-navbar-label">返回设置页面</span>
        </a>
        @endif
        @if($act == 'manage')
        <a href="javascript:void(0)" action="modify">
            <span class="ymicon-t-manage"></span><span class="am-navbar-label">修改</span>
        </a>
        @endif
        @if($act == 'requestJoin')
        <a href="javascript:void(0)" action="requestjoin">
            <span class="ymicon-t-request"></span><span class="am-navbar-label">申请参与</span>
        </a>
        @endif
        @if($act == 'viewJoinRequest')
        <a href="javascript:void(0)" action="showrequests">
            <span class="ymicon-t-list"></span><span class="am-navbar-label">处理申请</span>
        </a>
        @endif
        @if($act == 'confirmJoin')
        <a href="javascript:void(0)" action="confirmjoin">
            <span class="ymicon-t-agree"></span><span class="am-navbar-label">确认参与</span>
        </a>
        @endif
        @if($act == 'invite')
        <a href="javascript:void(0)" action="invite">
            <span class="ymicon-t-invite"></span><span class="am-navbar-label">邀请</span>
        </a>
        @endif
        @if($act == 'delivery')
        <a href="javascript:void(0)" action="delivery">
            <span class="ymicon-t-delivery"></span><span class="am-navbar-label">上传交付</span>
        </a>
        @endif
        @if($act == 'viewDelivery')
        <a href="javascript:void(0)" action="viewdelivery">
            <span class="ymicon-t-list"></span><span class="am-navbar-label">查看交付</span>
        </a>
        @endif
        @if($act == 'finish')
        <a href="javascript:void(0)" action="finish">
                <span class="ymicon-t-finish"></span><span class="am-navbar-label">完成任务</span>
        </a>
        @endif
        @if($act == 'cancel')
        <a href="javascript:void(0)" action="requestcancel">
                <span class="ymicon-t-cancel"></span><span class="am-navbar-label">取消任务</span>
        </a>
        @endif
        @if($act == 'cancelStatus')
        <a href="javascript:void(0)" action="cancelstatus">
            <span class="ymicon-t-cancel"></span><span class="am-navbar-label">查看取消进度</span>
        </a>
        @endif
        @if($act == 'confirmCancel')
        <a href="javascript:void(0)" action="cancelstatus">
            <span class="ymicon-t-cancel"></span><span class="am-navbar-label">对方请求取消，查看详情</span>
        </a>
        @endif
        @if($act == 'login')
        <a href="javascript:void(0)" action="login">
                <span class="ymicon-user2"></span><span class="am-navbar-label">登录</span>
        </a>
        @endif
        @if($act == 'running')
        <a href="javascript:void(0)" readonly="readonly">
        @if($task->step == App\Common\TaskStep::CANCEL)
            <span class="am-navbar-label">任务已经取消了</span>
        @elseif($task->step == App\Common\TaskStep::FINISH)
            <span class="am-navbar-label">任务已经完成</span>
        @elseif($task->step == App\Common\TaskStep::REVIEW)
            <span class="am-navbar-label">任务正在评审中，请给你喜欢的作品投票吧</span>
        @else
            <span class="am-navbar-label">任务进行中, 你现在只能看看</span>
        @endif
        </a>
        @endif
        @if($act == 'waitAgree')
        <a href="javascript:void(0)">
                <span class="am-navbar-label">您已经申请参加项目，等待发布者同意</span>
        </a>
        @endif
        @if($act == 'berejected')
        <a href="javascript:void(0)">
                <span class="am-navbar-label">您已经被发布者拒绝，暂时无法再申请参与</span>
        </a>
        @endif
        </li>
    @endforeach
    </ul>
</div>
@if(in_array('invite',$actions))
    @include('partview.addpanel', [ 'cols' =>1,
        'addFuncs'=>[
          '范围邀请'=>'/invite/createRange/'.$taskId.'/task',
          '指定邀请'=>'/invite/createDesignated/'.$taskId.'/task'
        ]
    ])
@endif
