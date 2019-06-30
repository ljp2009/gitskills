@extends('layouts.block')
@section('content')
	@section('serverLoad')
<link rel="stylesheet" href="/css/milestone.css">
@include('partview.headerbar',['left'=>'back',
    'center'=>'pageTitle',
    'right'=>'home',
    'pageTitle'=>'里程碑'])
<div class="ym_cm_card" style="min-height:100px">
@for($i=0;$i<count($items);$i++)
<?php
    $class = 'wait';
    $flag = '';
    if ($items[$i]->status == 'finish') {
        $class = 'finish';
    } else {
        if (!isset($position)) {
            $position = $i;
        }
        if ($items[$i]->isDelay) {
            $class = 'delay';
            $flag = '|已延迟';
        } elseif ($items[$i]->isActive) {
            $class = 'active';
            $flag = '|进行中';
        }
    }

?>
<div class="ym_milestone {{$class}}">
    <div class="header">{{$items[$i]->date}}&nbsp;&nbsp;<span>({{$items[$i]->statusName.$flag}})</span></div>
    <div class="body">{{$items[$i]->text}}</div>
    @if($items[$i]->allowSignIn)
    <button type="button" class="ctrl" onclick="signIn({{$items[$i]->id}})">确认</button>
    @endif
    <div class="position" id='ms{{$i}}'></div>
</div>
@endfor
</div>
@if(count($items) == 0)
<div class="ym_milestone empty"> 未设置里程碑 </div>
@endif
@show
@section('runScript')
<script type="text/javascript">
@if(isset($position))
$().ready(function(){
    window.location = '#ms{{$position}}';
});
@endif
function signIn(id){
    if(confirm('您确认这个里程碑的工作已经完成了吗？')){
        var url = '/milestone/signin';
        $.post(url, {
            'taskId':{{$taskId}},
            'id':id,
            '_token':$.ymFunc.getToken()
        }, function(data){
            if(data.res){
                location.reload();
            }else{
                alert('确认失败');
            }
        });
    }
}
</script>
@stop
