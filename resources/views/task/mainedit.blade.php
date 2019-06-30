@extends('layouts.block')
@section('title','任务设定')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
<link rel="stylesheet" href="/css/formpage.css">
<input type="hidden" id="taskId" value="{{$task->id}}"/>
<div class="ym_taskmg_body">
    <div class="ym_taskmg_page" id="mainPage">
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'任务管理'])
        <div class="ym_taskmg_desc">
            <i class="am-icon-info-circle"></i>&nbsp;&nbsp;当前任务模式为{{$task->getModelName()}}。
        </div>
        <div class="ym_taskmg_info">
            <div class="ym_taskmg_info_header">
               <span id="ym_param_title_show">{{$task->title}}</span>
                <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('title')">&nbsp;</i>
            </div>
        </div>
        <div class="ym_taskmg_item">
            任务奖金<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('amount')">&nbsp;</i>
            <span class="ym_taskmg_item_keyvalue" id="ym_param_amount_show">{{$task->amount}}</span>
        </div>
        <div class="ym_taskmg_item">
            交付日期<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('delivery_date')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$task->delivery_date}}</span>
        </div>
        <div class="ym_taskmg_item">
            任务分类
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('skill_type')" >&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">{{$task->getSkillName()}}</span>
        </div>
        <div class="ym_taskmg_split"></div>
        <div class="ym_taskmg_item">
            参与者技能限制<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('skill_level')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_skill_level_show">{{$task->joinCondition['skill']['value']}}</span>
            <input type="hidden" id="ym_param_skill_level_value" value="{{$task->joinCondition['skill']['key']}}" />
        </div>
        <div class="ym_taskmg_item">
            参与者信誉等级<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('credit_level')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_credit_level_show">{{$task->joinCondition['credit']['value']}}</span>
            <input type="hidden" id="ym_param_credit_level_value" value="{{$task->joinCondition['credit']['key']}}" />
        </div>
        @if($task->taskModel == 2 && false)
        <div class="ym_taskmg_item">
            评估机构
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('guarantee')">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_guarantee_show">{{$task->guaranteeName}}</span>
        </div>
        @endif
        @if($task->taskModel == 1)
        <div class="ym_taskmg_item" style="display:none">
            最大参与人数
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('max_partner_count')">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_max_partner_count_show">{{$task->max_partner_count==0?'不限制':$task->max_partner_count}}</span>
        </div>
        <div class="ym_taskmg_item">
            分配方案
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('assign_solution')">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_assign_solution_show">{{ $task->assign_solution['name'] }}</span>
            <input type="hidden" id="ym_param_assign_solution_value" value="{{ $task->assign_solution['id'] }}" />
        </div>
        @endif
        <div class="ym_taskmg_split"></div>
        <div class="ym_taskmg_item" onclick="showChildEditPage('detail',getId())">
            编辑任务的详细说明<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$task->isSetDetail?'(已设置)':'(未设置)'}}</span>
        </div>
        @if($task->task_type == 'simple')
        <div class="ym_taskmg_item" onclick="showChildEditPage('condition',getId())">
            编辑任务的评估条件<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$task->isSetCondition?'(已设置)':'(未设置)'}}</span>
        </div>
        <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/milestone/manage/'+getId())">
            编辑任务的里程碑<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$task->isSetMilestone?'(已设置)':'(未设置)'}}</span>
        </div>
        @endif
        <div class="ym_taskmg_desc">&nbsp;</div>
        <div style="height:50px;width:100%"></div>
        <div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
        <?php 
?>
          <ul class="am-navbar-nav am-cf am-avg-sm-{{count($bars)}}">
            @foreach($bars as $barAction)
            @if($barAction == 'delete')
              <li> <a href="javascript:void(0)" onclick="removeTask()">
                    <span class="am-icon-trash"></span>
                    <span class="am-navbar-label">删除</span>
              </a> </li>
            @elseif($barAction == 'preview')
              <li> <a href="javascript:void(0)" onclick="previewTask({{$task->id}})">
                    <span class="am-icon-sun-o"></span>
                    <span class="am-navbar-label">预览</span>
              </a> </li>
            @elseif($barAction == 'publish')
              <li> <a href="javascript:void(0)" onclick="publishTask()">
                <span class="am-icon-paper-plane"></span>
                <span class="am-navbar-label">发布</span>
              </a> </li>
            @elseif($barAction == 'cancel')
              <li> <a href="javascript:void(0)" onclick="cancelTask()">
                    <span class="am-icon-trash"></span>
                    <span class="am-navbar-label">取消</span>
              </a> </li>
            @elseif($barAction == 'back')
              <li> <a href="javascript:void(0)" onclick="$.ymFunc.back()">
                    <span class="am-icon-sun-o"></span>
                    <span class="am-navbar-label">返回详细页面</span>
              </a> </li>
            @endif
            @endforeach
          </ul>
        </div>
    </div>
    <div class="ym_taskmg_page" id="editPage">
    </div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除任务</div>
    <div class="am-modal-bd">
      你确定要删除这个任务吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript">
function removeTask(){
  setConfirmText('删除任务','你确定要删除这个任务吗？');
  $('#my-confirm').modal({
          onConfirm: function(e) {
              $.post('/pubtask/remove', { 'id':getId(),'_token':getToken() }
              , function(res){
                if(res.res){
                  window.location = '/taskhall';
                }
              }).error(function(e){
                alert(e.responsetext);
              });
          },
          onCancel: function(e) {
          }
      });
}
function publishTask(){
  setConfirmText('发布任务','你确定要发布这个任务吗？');
  $('#my-confirm').modal({
          onConfirm: function(e) {
              $.post('/pubtask/publish', { 'id':getId(),'_token':getToken() }
              , function(res){
                if(res.res){
                  window.location = '/task/'+getId();
                }else{
                    alert(res.desc);
                }
              }).error(function(e){
                alert(e.responsetext);
              });
          },
          onCancel: function(e) {
          }
      });
}
function cancelTask(){
  setConfirmText('发布任务','你确定要取消这个任务吗？');
  $('#my-confirm').modal({
          onConfirm: function(e) {
              $.post('/pubtask/cancel', { 'id':getId(),'_token':getToken() }
              , function(res){
                if(res.res){
                  window.location = '/task/'+getId();
                }else{
                    alert(res.desc);
                }
              }).error(function(e){
                alert(e.responsetext);
              });
          },
          onCancel: function(e) {
          }
      });
}
function setConfirmText(title,text){
    $('#my-confirm').find('.am-modal-hd').html(title);
    $('#my-confirm').find('.am-modal-bd').html(text);
}

showEditMainPage(true);

</script>
@stop
@stop
