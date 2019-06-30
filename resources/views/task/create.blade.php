@extends('layouts.block')
@section('title','创建任务')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
<input type="hidden" id="taskId" value="0"/>
<div class="ym_taskmg_body">
    <div class="ym_taskmg_page" id="mainPage">
        <div class="ym_backheader">
            <ul class="am-avg-sm-3">
                <li style="text-align:left" onclick="backToTaskHall()">
                    <i class="am-icon-angle-left"></i>
                    <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
                </li>
                <li style="text-align:center"><span class="ym_backheader_title">创建任务</span>
                </li>
                <li style="text-align:right">&nbsp;
                </li>
            </ul>
        </div>
        <div class="ym_taskmg_desc">
            <i class="am-icon-info-circle"></i>&nbsp;&nbsp;任务模式一旦选定则不能更改。
        </div>
        <div class="ym_taskmg_info">
            <div class="ym_taskmg_info_header">
               <input type="text" id="ym_param_title_show" class="ym_taskmg_info_header_input" placeholder="填写任务标题" />
            </div>
        </div>
        <div class="ym_taskmg_item">
            任务模式
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('task_model')" >&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_task_model_show">PK模式{{$defMode}}</span>
            <input type="hidden" id="ym_param_task_model_value" value="tenders" />

        </div>
        <div class="ym_taskmg_item">
            任务奖金<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('amount')">&nbsp;</i>
            <span class="ym_taskmg_item_keyvalue">金币</span>
            <span class="ym_taskmg_item_keyvalue" style="margin-right:0" id="ym_param_amount_show">1000</span>
        </div>
        <div class="ym_taskmg_item">
            交付日期<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('delivery_date')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{date("Y-m-d")}}</span>
        </div>
        <div class="ym_taskmg_item">
            任务分类
            <i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('skill_type')" >&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">资料</span>
            <input type="hidden" id="ym_param_skill_type_value" value="2001014" />
        </div>
        <div class="ym_taskmg_error">
        </div>
        <div class="ym_taskmg_desc">&nbsp;</div>
        <div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
              <ul class="am-navbar-nav am-cf am-avg-sm-1">
                  <li onclick="create();"> <a href="#">
                        <span class="am-icon-plus"></span><span class="am-navbar-label">创建</span>
                    </a> </li>
              </ul>
        </div>
    </div>
    <div class="ym_taskmg_page" id="editPage">
    </div>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript">
function create(){
  var title = $('#ym_param_title_show').val();
  var taskModel = $('#ym_param_task_model_value').val();
  var amount = $('#ym_param_amount_show').text();
  var deliveryDate = $('#ym_param_delivery_date_show').text();
  var skillType = $('#ym_param_skill_type_value').val();
  if(title.length == ''){
   showError('请填写任务标题。');
   return;
  }
  $.post('/pubtask/create', {
    'title':title,
    'taskModel':taskModel,
    'amount':amount,
    'deliveryDate':deliveryDate,
    'skillType':skillType,
    '_token':getToken()
  }, function(res){
   if(res.res){
     window.location = '/pubtask/manage-main/'+res.value;
   }
   else{
     showError('任务创建失败，请稍后再试。');
   }
  }).error(function(e){
    alert(e);
  });

}
showEditMainPage();
 </script>
@stop
@stop
