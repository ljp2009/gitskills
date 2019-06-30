
@extends('layouts.list')

@section('listcontent')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css">
@include('partview.headerbar',['left'=>'back',
    'center'=>'pageTitle',
    'right'=>'home',
    'pageTitle'=>'我的任务'])
@include('partview.tabswitchbar',[ 'list'=> [
    'join'=>['name'=>'我参与的任务', 'url'=>'/usertask/join/'],
    'publish'=>['name'=>'我发布的任务', 'url'=>'/usertask/publish/']
    ], 'active'=>$listName])
<div id="taskContainer" style="padding-bottom:50px;padding-top:70px;">
        <div class="ym_cm_list_none">没有符合条件的任务。</div>
</div>
@stop
@section('bindlist')
	list.bind({
        "container":"#taskContainer",
        "noneItem":"div#taskContainer>.ym_cm_list_none",
        "itemFeature":".ym_cm_card",
		"type":"usertask",
        "listName":"{{$listName}}",
        "parentId":{{$uid}},
		"pageIndex":{{$page}},
	});
@stop
