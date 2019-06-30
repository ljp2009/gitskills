<!--显示当前用户参加的任务-->
@extends('layouts.list')

@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'我的任务'])
<link rel="stylesheet" type="text/css" href="/css/ym_task.css">
@stop

@section('bindlist')
	list.bind({
		"type":"{{ $type }}",
		"parentId":{{ $uid }},
		@if(isset($listName))
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }}
	});
@stop
