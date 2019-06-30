@extends('layouts.block')
@section('title','取消任务')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'none', 'pageTitle'=>'取消任务'])
<div class="ym_cm_card">
    <div class="ym_taskmg_selector">
    <label>说明：</label>
    <?php $otherUserName = $isTaskOwner ? '合伙人' : '发起人'; ?>
    @if($isProposer)
    <p>
    您发出的取消申请正在等待{{$otherUserName}}确认，如果您的取消申请存在问题，您可以在{{$otherUserName}}确认之前撤销申请。
    </p>
    @else
    <p>
    {{$otherUserName}}请求取消这个任务。 
    </p>
    @endif
    </div>
</div>
@if($isProposer)
<form method="post" action="/task/undocancel">
@else
<form method="post" action="/task/confirmcancel">
@endif
    <input type="hidden" name="id" value="{{$task->id}}" />
    <input type="hidden" name="_token" value="" />
    <div class="ym_cm_card">
        <div class="ym_taskmg_selector">
            <label>取消原因：</label>
            <ul id="reasonlist">
                @if($cancelRequest->reason == 1)
                <li class="selected"><label>甲方不再需要这个任务了。</label></li>
                @elseif($cancelRequest->reason == 2)
                <li class="selected"><label>乙方无法在指定时间内按照交付条件交付</label></li>
                @elseif($cancelRequest->reason == 3)
                <li class="selected"><label>双方协定取消任务。</label></li>
                @endif 
                </ul>
            <pre>{{$cancelRequest->reason_text}} </pre>
            <div class="money" style="border:none;padding:0">
                <label>支付金额：</label>
                <span>金币</span>
                <input type="number" value="{{$cancelRequest->pay}}" readonly="readonly" name="pay" />
            </div>
        </div>
    </div>
</form>
<div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-1">
    @if($isProposer)
      <li onclick="sendRequest()"> <a href="#">
            <span class="ymicon-t-back"></span>
            <span class="am-navbar-label">撤销申请</span>
      </a> </li>
    @else
      <li onclick="sendRequest()"> <a href="#">
            <span class="ymicon-t-cancel"></span>
            <span class="am-navbar-label">同意取消</span>
      </a> </li>
    @endif
  </ul>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript">
function sendRequest(){
    $('input[name="_token"]').val($.ymFunc.getToken());
    $('form').submit();
}
 </script>
@stop
@stop
