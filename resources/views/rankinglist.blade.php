@extends('layouts.block')
@section('title','有妹首页')
@section('content')
	@section('serverload')
		@include('partview.headerbar',['left'=>'backTo', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'排行榜', 'backUrl'=>'/reshall'])
	<!--顶部导航-->
    <link href="/css/ym_reshall.css" rel="stylesheet" />
    <link href="/css/ym_recomend.css" rel="stylesheet" />
    <link href="/css/ipdetail.css" rel="stylesheet" />
    <!-- 榜单 分类  达人 集市 顶部导航 -->
    @include('partview.quicknav',['active'=>'ranking'])
    <div class="ym_cm_card">
        <div class="ym_cm_cardheader" style="border-bottom:solid 1px #e2e2e2">
            IP榜 <span onclick="$.ymFunc.goTo('/ranking/list/ip/0')">更多 &nbsp;<i class="ymicon-right"></i></span>
        </div>
        <div class="ym_lzdiv" viewpath="/ranking/part/ip" style="min-height:10rem"></div>
    </div>
    <div class="ym_cm_card">
        <div class="ym_cm_cardheader">
            作品榜 <span onclick="$.ymFunc.goTo('/ranking/list/prod/0')">更多 &nbsp;<i class="ymicon-right"></i></span>
        </div>
        <div class="ym_lzdiv" viewpath="/ranking/part/prod" style="min-height:10rem"></div>
    </div>
    <div class="ym_cm_card">
        <div class="ym_cm_cardheader no_border">
            评论榜 <span onclick="$.ymFunc.goTo('/ranking/list/disc/0')">更多 &nbsp;<i class="ymicon-right"></i></span>
        </div>
        <div class="ym_lzdiv" viewpath="/ranking/part/disc" style="min-height:10rem"></div>
    </div>
	@stop
    @parent
@stop
