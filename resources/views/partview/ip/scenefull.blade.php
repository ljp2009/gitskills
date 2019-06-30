@extends('layouts.list')

@section('listheader')
  @include('partview.detailheader')
<!--经典台词标题部分-->
  <div class="am-container ym-ft-15 ym-c-bblack ym-r-header" >
	  经典场景
	  <a href="##" class="am-fr">
		  <i class="am-icon-angle-right am-icon-sm ym-c-black"></i>
	  </a>
  <hr />		
  </div>
    <div id="container">
  </div>
  <button id="listControlBtn"></button>
@stop

@section('bindlist')
  list.bind({
    "container":"#container",
    "type":"ipscene",
    "listName":"verified",
    "parentId":{{ $ipid }},
    "pageIndex":{{ $page }},
    "itemFeature":"oneitem"
  });
@stop
