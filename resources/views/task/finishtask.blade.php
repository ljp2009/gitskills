@extends('layouts.block')
@section('title','完成任务')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'none', 'pageTitle'=>'完成任务'])
<div class="ym_cm_card">
    <div class="ym_taskmg_selector">
    <label>说明：</label>
    <p>
    您正在确认完成任务“{{$task->title}}”。 请确认乙方已经正常完成了交付。
    </p>
    <p>
    任务结束后，我们将支付奖金给对方，并且关闭任务的交付入口。
    </p>
    </div>
</div>
<form method="post" action="/task/finish">
    <input type="hidden" name="id" value="{{$task->id}}" />
    <input name="_token" type="hidden" />
    <input name="score" type="hidden" value='4'/>
    <div class="ym_cm_card">
        <div class="ym_taskmg_selector">
            <label>请为您的合作者打个分：</label>
            <div class="scorebox">
                <i class="ymicon-star selected"></i>
                <i class="ymicon-star selected"></i>
                <i class="ymicon-star selected"></i>
                <i class="ymicon-star selected"></i>
                <i class="ymicon-star"></i>
            </div>
        </div>
    </div>
</form>
<div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-1">
      <li onclick="sendRequest()"> <a href="#">
            <span class="ymicon-t-finish"></span>
            <span class="am-navbar-label">完成任务</span>
      </a> </li>
  </ul>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript">
$(document).ready(function(){
   var $boxItems = $('.scorebox').find('i');
   $boxItems.on('click', function(){
       var index = $boxItems.index(this);
       for(var i=0; i<=$boxItems.length; i++){
           if(i<=index){
               if(!$($boxItems[i]).hasClass('selected')){
                   $($boxItems[i]).addClass('selected');
               }
           }else{
               $($boxItems[i]).removeClass('selected');
           }
       }
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
