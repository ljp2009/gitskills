<!--显示用户参加任务的申请-->
@extends('layouts.list')
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'参与申请'])
	<div  style="padding-top:0rem" id="userlistback">
		<div id='userlist'></div>
	</div>

<!--<link rel="stylesheet" type="text/css" href="/css/ym_task.css">-->
<div  class="am-modal am-modal-confirm am-modal-prompt" tabindex="-1" id="my-prompt">
	<div class="am-modal-dialog">
		<div class="am-modal-hd" id='my-prompt-title'>拒绝理由</div>
		<div class="am-modal-bd" id='my-prompt-content'>

		</div>
		<div class="am-modal-footer">
			<span class="am-modal-btn" data-am-modal-cancel>取消</span>
			<span class="am-modal-btn" id="btn_confirm" data-am-modal-confirm>提交</span>
		</div>
	</div>
</div>
@stop
@section('bindlist')
	list.bind({
        "container":"#userlist",
        'itemFeature':".ym-line-list-item",
		"type":"{{ $type }}",
		"parentId":{{ $id }},
		@if(isset($listName))
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }},
	});
	//<script type="text/javascript">
function operation(obj,action)
{
    var id = $(obj).attr('userid');
    var name = $(obj).attr('username');
    var taskId = $(obj).attr('relid');
	var confirmControl = $('#my-prompt');
	var titleControl = $('#my-prompt-title');
	var content = $('#my-prompt-content');
    if(action == 'agree'){
        titleControl.html('同意合作');
        @if($task->model == App\Common\TaskModel::PK)
        content.html('您正正在同意'+name+'参与您的PK任务，一旦同意则无法撤销。');
        @elseif($task->model == App\Common\TaskModel::APPOINT)
        content.html('您正在向'+name+'发送同意合作的申请,一旦发送合作申请您将无法再修改任务，您可以向多个人发出合作申请，最先接受您申请的人将会成为您的合作伙伴，同时任务将进入交付阶段，您确定要这样做吗？');
        @endif
    }
    if(action == 'reject'){
        titleControl.html('拒绝合作');
        content.html('您确认拒绝'+name+'参与您的任务吗，用户被拒绝后将无法再次申请参与您的任务？');
    }
    if(action == 'undo'){
        titleControl.html('撤销操作');
        content.html('您确认撤销对'+name+'的操作吗，撤销后用户状态将变回“申请合作”？');
    }

    if(action == 'communicate'){
        window.location = "/private/list/dialog/0/"+id;
        return;
    } else {
    	$('#btn_confirm').html('提交');
    }
	confirmControl.modal({
    relatedTarget: {'userid':id, 'taskid':taskId, 'name':name, 'action':action},
	onConfirm: function(e) {
		//沟通
		if (action == 'communicate') {
			window.location = "/private/list/dialog/0/"+id;
			return;
		};
		var obj = this.relatedTarget;
        var postUrl = "/jointask/"+obj.action;
        var postData = {"userid":obj.userid, "taskid":obj.taskid,'_token':"{{ csrf_token() }}"};
		$.ajax({
			type:'POST',
			url:postUrl,
			data:postData,
			dataType:'json',
			success:function(data){
				if(data.res == true)
				{
					window.location.reload();
				}
				else
				{
					alert(data.desc);
				}
            },
            error:function(a,b,c){
                alert(a+b+c);
            }
		});
	},onCancel: function(e) {}});

}


@stop
