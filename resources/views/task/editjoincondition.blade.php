@extends('layouts.block')
@section('title','有妹社区')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
<input type="hidden" id="taskId" value="{{$task->id}}"/>
<div class="ym_taskmg_body">
    <div class="ym_taskmg_page" id="mainPage">
        <div class="ym_backheader">
            <ul class="am-avg-sm-3">
                <li style="text-align:left" onclick="back()">
                    <i class="am-icon-angle-left"></i>
                    <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
                </li>
                <li style="text-align:center"><span class="ym_backheader_title">参与条件</span>
                </li>
                <li style="text-align:right">&nbsp;
                </li>
            </ul>
        </div>
        <div class="ym_taskmg_desc">
            <i class="am-icon-info-circle"></i>&nbsp;&nbsp;设置任务“{{$task->title}}”的参与条件
        </div>
        <div class="ym_taskmg_item_top">
            技能等级<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('skill_level')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_skill_type_value">{{$task->joinCondition['skill']['value']}}</span>
            <input type="hidden" id="ym_param_skill_type_key" value="{{$task->joinCondition['skill']['key']}}" />
        </div>
        <div class="ym_taskmg_item">
            信誉等级<i class="am-icon-pencil ym_taskmg_gotoicon" onclick="showEditPartview('credit_level')">&nbsp;</i>
            <span class="ym_taskmg_item_value" id="ym_param_credit_level_value">{{$task->joinCondition['credit']['value']}}</span>
            <input type="hidden" id="ym_param_credit_level_key" value="{{$task->joinCondition['credit']['key']}}" />
        </div>
        <div class="ym_taskmg_desc">&nbsp;&nbsp;您可以设置参与这个任务的用户最低标准，当用户的技能和信誉等级均满足或者高于这个标准的时候才可以申请参与这个项目。</div>
    </div>
    <div class="ym_taskmg_page" id="editPage">
    </div>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript">
showEditMainPage();
</script>
@stop
@stop
