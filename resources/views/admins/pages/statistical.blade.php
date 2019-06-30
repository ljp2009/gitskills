@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>任务控制</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">用户访问情况统计</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-1 am-text-right">周期：</div>
          <div class="am-u-sm-8 am-u-md-2">
            <select name="type" id="p_type">
                <option value="1">日</option>
                <option value="2">周</option>
                <option value="3">月</option>
            </select>
          </div>
          <div class="am-u-sm-4 am-u-md-1 am-text-right">日期：</div>
          <div class="am-u-sm-8 am-u-md-2">
            <input id="p_date" name="date" type ="date" value="{{date('Y-m-d')}}"/>
          </div>
          <div class="am-u-sm-12 am-u-md-6 am-text-left">
            <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="searchSession()"> 查询 </button>
            <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="gotoSessionDetail()"> 查看详情 </button>
          </div>
        </div>
        <div class="am-g am-margin-top" style="margin-bottom:20px">
          <ul class="am-avg-sm-1 am-avg-md-4 am-padding am-text-center admin-content-list ">
            <li>总会话数:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="total_session">(未查询)</a></li>
            <li>总会话时长:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="total_session_long">(未查询)</a></li>
            <li>平均会话时长:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="avg_session_long">(未查询)</a></li>
            <li>用户数:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="total_user">(未查询)</a></li>
          </ul>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">新增用户与留存率</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-1 am-text-right">日期：</div>
          <div class="am-u-sm-8 am-u-md-2">
            <input id="u_date" name="date" type ="date" value="{{date('Y-m-d')}}"/>
          </div>
          <div class="am-u-sm-12 am-u-md-6 am-text-left">
            <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="searchUserCt()"> 查询 </button>
          </div>
        </div>
        <div class="am-g am-margin-top" style="margin-bottom:20px">
          <ul class="am-avg-sm-1 am-avg-md-4 am-padding am-text-center admin-content-list ">
            <li>新增用户数:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="user_ct">(未查询)</a></li>
            <li>1日留存:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="user_ct_r1">(未查询)</a></li>
            <li>3日留存:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="user_ct_r3">(未查询)</a></li>
            <li>7日留存:&nbsp;&nbsp;<a href="#" class="am-text-warning" id="user_ct_r7">(未查询)</a></li>
          </ul>
        </div>
    </div>
</div>
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">

function gotoSessionDetail(){
        var type = $('#p_type').val();
        var dt   = $('#p_date').val();
        window.location.href = '/admin/st/detail-session/'+type+'/'+dt;
}
function searchSession(){
    $.post('/admin/st/visit-session',{
        'type' : $('#p_type').val(),
        'date' : $('#p_date').val()
    },function(data){
        if(data.res){
            $('#total_session').text(data.totalSession+'次');
            $('#total_session_long').text(data.totalSessionLong+'秒');
            $('#avg_session_long').text(data.avgSession+'秒');
            $('#total_user').text(data.totalUser+'人');
        }else{
            alert('查询失败。');
        }
    });
}
function searchUserCt(){
    $.post('/admin/st/user-count',{
        'type' : $('#p_type').val(),
        'date' : $('#u_date').val()
    },function(data){
        if(data.res){
            $('#user_ct').text(data.ct+'人');
            $('#user_ct_r1').text(data.r1+'%');
            $('#user_ct_r3').text(data.r3+'%');
            $('#user_ct_r7').text(data.r7+'%');
        }else{
            alert('查询失败。');
        }
    });
}
</script>
@stop
