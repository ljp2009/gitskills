@extends('layouts.list')
<link href="/css/ym_recomend.css" rel="stylesheet" />
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'更多推荐'])
	<div class="ym-line-list" id="deflist">
	</div>
@stop

@section('bindlist')
	list.bind({
		"container":"#deflist",
		"type":"{{ $type }}",
		"loadSize":1,
        "itemFeature":".ym-line-list-group",
		"maxSize":4,
		"listName":"recommend",
		"pageIndex":{{ $page }}
	});
@stop
