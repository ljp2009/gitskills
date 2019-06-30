@extends('layouts.list')
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle',
        'right'=>'home','pageTitle'=>isset($title)?$title:'用户列表'])
     <link href="/css/ym_userlist.css?a=1" rel="stylesheet" />
     <div id="userlist" style="padding-top:1.5rem"></div>
@stop

@section('bindlist')
	list.bind({
		"container":"#userlist",
		"type":"user",
		"listName":"{{$listName}}",
		@if(!empty($pid))
		"parentId":{{ $pid }},
		@endif
		"pageIndex":{{ $page }},
	});
@stop
