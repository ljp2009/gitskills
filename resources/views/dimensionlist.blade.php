@extends('layouts.list')
@section('title')
{{$name}}
@stop
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>$name])
<script type="text/javascript" src="/js/ym_dimension.js"></script>
<link rel="stylesheet"  href="/css/ym_dimension.css">
<div class="dimension-list" id="dimension-list"></div>

@stop
@section('bindlist')
	list.bind({
		"container":"#dimension-list",
		"loadSize":8,
		"type":"dimension",
		"pageIndex":{{ $page }},
        "listName" :"{{$listName}}",
        "parentId":{{$id}}
	});
@stop
