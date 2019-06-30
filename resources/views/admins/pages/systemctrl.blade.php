@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>任务控制</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">操作</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
    <div class="am-g am-margin-top">
      <div class="am-u-sm-4 am-u-md-2 am-text-right">说明：</div>
      <div class="am-u-sm-8 am-u-md-10">
        当前页面是用来模拟PK任务到达某天后的自动处理过程。
        <br/>点击模拟结算后，相应的任务。 会按照指定日期进行处理。
        <br/><b style="color:red">此过程不可逆，仅用于测试使用。</b>
      </div>
    </div>
    <div class="am-g am-margin-top">
    </div>
    <div class="am-g am-margin-top">
      <div class="am-u-sm-4 am-u-md-2 am-text-right">模拟结算时间：</div>
      <div class="am-u-sm-8 am-u-md-10">
        <input id="taskTime" type ="date" value="{{date('Y-m-d')}}"/>
      </div>
    </div>
    <div class="am-g am-margin-top" style="margin-bottom:20px">
      <div class="am-u-sm-4 am-u-md-2 am-text-right">操作：</div>
      <div class="am-u-sm-8 am-u-md-10">
        <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="runSchedule()">
            执行“PK任务”的批处理
        </button>
        <pre id="info"></pre>
      </div>
    </div>
    </div>
</div>
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">
function runSchedule(){
    $.post('/admin/schedule/pk-schedule', {
        '_token':'{{csrf_token()}}',
        'date':getDate()
    },function(d){
        if(d.res){
            $('#info').html(d.info);
        }else{
            alert('执行失败');
        }
    });
}
function getDate(){
    return $('#taskTime').val();
}
</script>
@stop
