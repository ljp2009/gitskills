@extends('layouts.block')
@section('title','取消任务')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'none', 'pageTitle'=>'取消任务'])
<div class="ym_cm_card">
    <div class="ym_taskmg_selector">
    <label>说明：</label>
    <p>
    您正在取消任务“{{$task->title}}”。 由于任务正处于交付阶段，取消任务需要甲乙双方确认。<br />
    </p>
    <p>
    请填写以下取消原因，提交取消申请，对方确认之后，系统将自动取消任务。取消原因一旦填写则无法修改，但是您可以在对方确认之前撤销取消申请。<br/>
    </p>
    <p>
    如果需要甲方支付乙方部分费用，可以天下支付金额，0表示不需要支付。
    </p>
    </div>
</div>
<form method="post" action="/task/requestcancel">
    <input type="hidden" name="id" value="{{$task->id}}" />
    <input type="hidden" name="_token" value="" />
    <div class="ym_cm_card">
        <div class="ym_taskmg_selector">
            <label>取消原因：</label>
            <ul id="reasonlist">
                <li value='1'><label>甲方不再需要这个任务了。</label></li>
                <li value='2'><label>乙方无法在指定时间内按照交付条件交付</label></li>
                <li value='3'><label>双方协定取消任务。</label></li> </ul>
            <input type="hidden" name="reason" value="1" />
        </div>
        <div class="ym_taskmg_selector">
            <label>备注：</label>
            <textarea rows="5" resizeable='false'  name="reason_text" >{{$reason_text or ''}}</textarea>
        </div>
        <div class="ym_taskmg_selector">
            <div class="money">
                <label>支付金额：</label>
                <span>金币</span>
                <input type="number"  value="{{$pay or 0}}"  name="pay" />
            </div>
        </div>
    </div>
</form>
<div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-1">
      <li onclick="sendRequest()"> <a href="#">
            <span class="ymicon-t-cancel"></span>
            <span class="am-navbar-label">提交申请</span>
      </a> </li>
  </ul>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript">
$(document).ready(function(){
   var $lis = $('#reasonlist').find('li');
   $lis.on('click', function(){
       if($(this).hasClass('selected')) return;
       $lis.removeClass('selected');
       $(this).addClass('selected');
       $('input[name=reason]').val($(this).attr('value'));
   });
   $('#reasonlist').find('li[value={{$reason or 1}}]').click();
});
function sendRequest(){
    $('input[name="_token"]').val($.ymFunc.getToken());
    $('form').submit();
}
 </script>
@stop
@stop
