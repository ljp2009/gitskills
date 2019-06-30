@extends('layouts.list')

@section('listheader')
	@include('partview.detailheader',array('hideShare'=>true))
<div class="my-works">
    <div class="am-container" id="works"> </div>
</div>
@stop
@section('bindlist')
//<script >
list.bind({
    "container":"#works",
    "type":"prod",
    "listName":"{{$pagetype}}-{{$order}}",
    "parentId":{{ $id }},
    "pageIndex":{{ $page }},
    "controlBtn":"#listControlBtn"
});
@stop

