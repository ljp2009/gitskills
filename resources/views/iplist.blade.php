@extends('layouts.list')

@section('listheader')
	@include('partview.detailheader',array('hideShare'=>true, 'showSearch'=>true))
@stop

@section('bindlist')
	list.bind({
		"type":"{{ $type }}",
		"loadSize":"8",
		@if(isset($listName))
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }}
	});
@stop
