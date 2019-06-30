@extends('layouts.list')
@section('title')
浏览历史记录
@stop
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'浏览历史记录'])
<div class="myhistory-list" id="myhistory-list"></div>

@stop
@section('bindlist')
	list.bind({
		"container":"#myhistory-list",
		"loadSize":10,
		"type":"myhistory",
		"pageIndex":{{ $page }}
	});
@stop
