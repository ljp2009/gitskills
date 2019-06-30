<!--查看任务信息-->
@extends('layouts.block')
@section('content')
@section('serverLoad')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css" />
<link rel='stylesheet' href='/css/ym_publish.css' />
<link rel="stylesheet" href="/css/milestone.css" />
<link rel="stylesheet" type="text/css" href="/css/list.css" />
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'任务详情'])
<div class="ym_cm_card ym_content_block" >
    <div class= "ym_title_row">
     任务：   <span>{{$task->title}}</span>
    </div>
    <div class="ym_user_row">
        <div class="ym_user_header_div">
            <img class="ym_user_header_img"
                src="{{$task->user->avatar->getPath(2,'80w_80h_1e_1c')}}"
                onclick="$.ymFunc.goTo('{{$task->user->homeUrl}}')"/>
        </div>
        <div class="ym_user_info_div">
            <div class="ym_user_info_name">{{$task->user->display_name}}&nbsp;&nbsp;发布</div>
            <div class="ym_user_info_time">发布时间:&nbsp;{{$task->publish_date}}</div>
        </div>
    </div>
    <div class="ym_task_attr_row">
       <div class="ym_task_attr_tag">
        {{$task->getModelName()}}&nbsp;|&nbsp;{{$task->getSkillName()}}&nbsp;|&nbsp;{{$task->getStepName()}}
        </div>
       <div class="ym_task_attr_time">
        交付时间：{{$task->delivery_date}}
       </div>
    </div>
    <div class="ym_task_amount_row">
       <div class="ym_task_amount_value">
            {{$task->amount}}
        </div>
       <div class="ym_task_amount_label">
           &nbsp;&nbsp; 完成奖励（金币）
       </div>
    </div>
@if($task->step == App\Common\TaskStep::DELIVERY)
    <div class="ym_partner_row">
        <div class="ym_user_header_div">
            <img class="ym_user_header_img"
                src="{{$task->appointPartner->user->avatar->getPath(2,'80w_80h_1e_1c')}}"
                onclick="$.ymFunc.goTo('{{$task->appointPartner->user->homeUrl}}')"/>
        </div>
        <div class="ym_user_info_div">
            <div class="ym_user_info_name">{{$task->appointPartner->user->display_name}}&nbsp;&nbsp;正在执行这个任务。</div>
        </div>
    </div>
@else
    <div style="clear:both"></div>
@endif
</div>
<?php 
use app\Common\TaskModel;
use app\Common\TaskStep;
?>
<div class="ym_cm_card ym_content_block" >
    <div class="ym_tabswitchbar partview">
        @if($task->model == TaskModel::APPOINT)
        <ul class="ym_avg_3">
            <li class="tabItem" href="#tab1">任务说明</li>
            <li class="tabItem" href="#tab2">交付条件</li>
            <li class="tabItem" href="#tab5">里程碑</li>
        </ul>
        @endif
        @if($task->model == TaskModel::PK)
        <ul class="ym_avg_2">
            <li class="tabItem" href="#tab1">任务说明</li>
        @if($task->step == TaskStep::REVIEW)
            <li class="tabItem" href="#tab6">参与作品</li>
        @else
            <li class="tabItem" href="#tab4">参与人</li>
        @endif
        </ul>
        @endif
    </div>
	<div class="ym_up_info_text partview hidden" id='tab1' viewpath="/task/{{ $task->id }}/taskdesc"></div>
	<div class="ym_up_info_text partview hidden" id='tab2' viewpath="/task/{{ $task->id }}/taskcondition"></div>
	<div class="ym_up_info_text partview hidden" id='tab3' viewpath="/task/{{ $task->id }}/tasktimeline"></div>
	<div class="ym_up_info_text partview hidden" id='tab4' viewpath="/task/{{ $task->id }}/taskpartner"></div>
	<div class="ym_up_info_text partview hidden" id='tab5' viewpath="/milestone/list/{{$task->id}}"></div>
	<div class="ym_up_info_text partview hidden" id='tab6' viewpath="/taskdelivery/partview/{{ $task->id }}"></div>
</div>
    <div style = "width:100%;height:50px"></div>
    <!--任务操作-->
@if(count($actions)>0)
    <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
		<div class="am-modal-dialog">
			<div class="am-modal-hd"></div>
			<div class="am-modal-bd" id='my-confirm-content'>
			</div>
			<div class="am-modal-footer">
				<span class="am-modal-btn" data-am-modal-cancel>取消</span>
				<span class="am-modal-btn" data-am-modal-confirm>确定</span>
			</div>
		</div>
	</div>
	<div class="am-modal am-modal-alert" tabindex="-2" id="my-alert">
		<div class="am-modal-dialog">
			<div class="am-modal-hd">提示</div>
			<div class="am-modal-bd">
			</div>
			<div class="am-modal-footer">
				<span class="am-modal-btn">确定</span>
			</div>
		</div>
	</div>
@include('task.partview.ctrlbar', ['taskId'=>$task->id])
    <div class="am-modal-actions" id="my-actions">
    <div class="am-modal-actions-group">
        <ul class="am-list">
          <li>
            <a href="/invite/createRange/{{$task->id}}/0/{{$task->user_id}}">范围邀请</a>
          </li>
          <li >
            <a href="/invite/createDesignated/{{$task->id}}/0/{{$task->user_id}}">指定邀请</a>
          </li>
        </ul>
    </div>
    <div class="am-modal-actions-group">
        <button class="am-btn am-btn-secondary am-btn-block" data-am-modal-close>取消</button>
    </div>
@endif
	@show
	@parent
	@section('runScript')
    @if(count($actions)>0)
    <script type="text/javascript" src="/js/showtask.js"></script>
    @endif
    <script type="text/javascript">
    $(document).ready(function(){
        var $tabs =  $('.tabItem');
        if(typeof(lasy) != "undefined"){
            lasy.bindManualControl($tabs);
        }
        $tabs.on('click', function(){
            var $this = $(this);
            if($this.hasClass('ym_active')) return;
            $tabs.removeClass('ym_active');  
            $this.addClass('ym_active');
            var $target = $($(this).attr('href'));
            $tabPages = $('.ym_up_info_text'); 
            $tabPages.hide();
            $target.show();
        });
        @if($task->step==App\Common\TaskStep::REVIEW)
        $tabs.last().click();
        @else
        $tabs.first().click();
        @endif

    });
	</script>
	@stop
@stop
