
@extends('layouts.list')

@section('listcontent')
<script type="text/javascript" src="/js/ym_dimension.js"></script>
<link rel="stylesheet"  href="/css/ym_dimension.css">
@include('partview.headerbar',['left'=>'back',
    'center'=>'pageTitle',
    'right'=>'home',
    'pageTitle'=>'我的关注'])
@include('partview.tabswitchbar',[ 'list'=> [
    
    'production'=>['name'=>'用户动态', 'url'=>'/userfollow/list/user-production/0'],
    'dimension'=>['name'=>'入驻次元', 'url'=>'/userfollow/list/user-dimension/0']
    ], 'active'=>$type])


<div class="dimension-list" id="dimension-list" style="padding-top:58px;">
        <div class="ym_cm_list_none">没有符合条件的任务。</div>
</div>
@stop
@section('bindlist')
	list.bind({
        "container":"#dimension-list",
        "noneItem":"div#dimension-list>.ym_cm_list_none",
		"type":"userfollow",
		"pageIndex":{{ $page }},
        "itemFeature":".ym_cm_listitem",
        "listName":"user-{{$type}}"
	});
@stop
