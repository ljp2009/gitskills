@extends('layouts.list')
@section('title')
{{$name}}
@stop
@section('content')
@section('listheader')

	@include('partview.detailheader',array('hideShare'=>true))

<div id="skill">

</div>

@stop
@parent
@section('bindlist')
	list.bind({
		"container":"#skill",
		"loadSize":8,
		"type":"roleskill",
		"parentId":{{ $id }},
		"pageIndex":{{ $page }}
	});
	
@stop
@stop