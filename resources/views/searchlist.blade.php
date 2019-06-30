@extends('layouts.list')
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'查询结果'])
	<div class="ym-line-list ym-line-list-group" id="deflist">
        <div class="ym-line-list-group-info">以下是<span style="color:#ef7c1e;">"{{$listName}}"</span>的查询结果，您也可以<a href="/ip/create">添加</a>一个。</div>
	</div>
@stop

@section('bindlist')
	list.bind({
		"container":"#deflist",
		"type":"{{ $type }}",
		"loadSize":4,
		"maxSize":16,
        "itemFeature":".ym-line-list-item",
		@if(isset($listName))
			"listName":"{{$listName}}",
		@endif
		"pageIndex":{{ $page }}
	});
@stop
