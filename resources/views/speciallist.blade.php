@extends('layouts.list')
<link href="/css/ym_recomend.css" rel="stylesheet" />
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'更多专辑'])
	<div class="ym-special-list">
	    <div class="am-g" style=" padding:0 10px 10 10px; background-color:#ffffff; margin:15px 0 15px 0; border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;">
	    	<ul id="deflist">
	    	</ul>
	    </div>
	</div>
@stop

@section('bindlist')
	list.bind({
		"container":"#deflist",
		"type":"{{ $type }}",
		"loadSize":8,
        "itemFeature":".ym_special_item",
		"maxSize":16,
		"pageIndex":{{ $page }}
	});
@stop
