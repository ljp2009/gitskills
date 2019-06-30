@extends('layouts.block')
@section('title','有妹首页')
@section('content')
	@section('serverload')
		@include('partview.headerbar',['left'=>'user', 'center'=>'logo', 'right'=>'search'])
	<!--顶部导航-->
    <link href="/css/ym_reshall.css" rel="stylesheet" />
    <link href="/css/ym_recomend.css" rel="stylesheet" />
    <!-- 榜单 分类  达人 集市 顶部导航 -->
    @include('partview.quicknav',['active'=>'none'])
		<!--首页推荐-->
	<div class="hall-container">
		<div class="hall-banner ym_cm_card">
			<div class="am-slider am-slider-default am-my-slider" data-am-flexslider id="demo-slider-0">
			  <ul class="am-slides">
			    @if(isset($list) && count($list)>0)
		        @foreach($list as $banner)
		        <li onclick="window.location = '{{$banner->url}}'">
		            <img src="{{$banner->imagePath->getPath(1,'600w_300h_1e|600x300-2rc')}}" />
		            <h3 style="">{{$banner->description}}</h3>
		        </li>
		        @endforeach
		        @else
		        <li style="text-align:center;">
		            <h3>暂无推荐</h3>
		        </li>
		        @endif
			  </ul>
			</div>
		</div>
        <!--活动-->
	  	<!--<div class="ym_lzdiv ym_cm_card" viewpath="/custom/ido21/cover" style="min-height:10rem;background-color:#ffffff"></div>-->
	  	<div class="ym_lzdiv ym_cm_card" viewpath="/reshall/activitys" style="min-height:10rem;background-color:#ffffff;"></div>
			<!--有妹推荐-->
	  	<div class="ym_lzdiv ym_cm_card" viewpath="/reshall/recommends" style="min-height:10rem;background-color:#ffffff"></div>
			<!--专辑-->
	  	<div class="ym_lzdiv ym_cm_card" viewpath="/reshall/specials" style="min-height:20rem;background-color:#ffffff"></div>
            <!--那些大神-->
	  	<div class="ym_lzdiv ym_cm_card" viewpath="/reshall/masters" style="min-height:10rem;background-color:#ffffff"></div>
			<!--次元列表-->
	  	<div class="ym_lzdiv ym_cm_card" viewpath="/reshall/dimensions" style="min-height:10rem;background-color:#ffffff;"></div>
        @include('partview.footerbar', ['page'=>'main','addFuncs'=>['发布IP'=>'/ip/create', '创建次元'=>'/dimension/create']])
    </div>
	@stop
    @parent
    @section('runScript')
        @if(isset($vote))
        @include('vote.votepage', $vote)
        @endif
    <script type="text/javascript" src="/js/ym_vote.js"></script>
    <script type="text/javascript" src="/js/ym_sign.js"></script>
@if(isset($first_login) && $first_login)
    <script type="text/javascript" src="/js/ym_user_wizzard.js"></script>
@endif
    <script>
        $.ymPopMenu.bind({
        'menus':[
            {'text':'发布IP', 'url':'/ip/create'},
            {'text':'创建次元', 'url':'/dimension/create'},
        ]
        });
        @if(auth::check())
        $(document).ready(function(){
            var vtCtrl = new voteCtrl();
            vtCtrl.check();
        });
        @endif
    </script>
    @stop
@stop
