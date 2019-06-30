@extends('layouts.list')
@section('title',$title)
@section('listcontent')
    <link href="/css/ipdetail.css" rel="stylesheet" />
    <link href="/css/ym_userlist.css" rel="stylesheet" />
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
<?php
$actName = 'ranking';
if ($listName == 'user') {
    $actName = 'master';
}
?>
    @include('partview.quicknav',['active'=>$actName])
    @if($listName == 'disc')
    <div class="ym_cm_card" id="morerankingcontainer"></div>
    @else
    <div style="width:100%" id="morerankingcontainer"></div>
    @endif
@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#morerankingcontainer",
    "type":"ranking",
    "pageIndex":{{ $page }},
    "listName":"{{$listName}}",
    "itemFeature":"{{$listName=='disc'?'.ym_ip_content':'.ym-line-list-item'}}",
});
@stop


