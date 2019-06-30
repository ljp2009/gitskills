@extends('layouts.list')

@section('listheader')
@include('partview.detailheader')

<div class="am-userInfo">
		<div class="filter-bg"></div>
		<div class="am-user-box">
			<h5 class="ym-ft-12">超级无敌潜水客</h5>
			<div class="am-user-photo">
				<img src="\imgs/user/user1.jpg" alt="" class="am-img-responsive am-circle">
			</div>
			<div class="am-u-mi-7 am-u-sm-7 am-user-info">
				<h3>用户名称比较长</h3>
				<div class="am-info">
					<i class="am-icon-venus am-icon-sm"></i>
					<div class="am-person">
						<span class="am-age">19岁</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="am-marrage">Single</span>
					</div>
				</div>
				<div class="am-user-tag">
					关注&nbsp;&nbsp;<span class="am-attention-num">26</span>&nbsp;&nbsp;|&nbsp;&nbsp;粉丝&nbsp;&nbsp;
					<span class="am-fans-num">35</span>
				</div>
				<div class="am-user-tag">
					<span>手绘、配乐、编剧、导演</span>
				</div>
			</div>
		</div>
		<p class="am-sign">这里是签名档签名档，假字不在回家的路上就在上班的路上。</p>
		<div class="am-container">
			<a href=""><img src="\imgs/v.png" alt="" class="am-img-responsive"></a>
			<a href=""><img src="\imgs/youtube.png" alt="" class="am-img-responsive"></a>
			<a href=""><img src="\imgs/it.png" alt="" class="am-img-responsive"></a>
			<a href=""><img src="\imgs/we.png" alt="" class="am-img-responsive"></a>
			<a href=""><img src="\imgs/s6.png" alt="" class="am-img-responsive"></a>
		</div>
		<div class="am-handel">
			<span><i class="am-icon-eye"></i>已关注</span>
			<span><i class="am-icon-envelope-o"></i></i>私信</span>
			<span><i class="am-icon-thumbs-o-up"></i>打赏</span>
		</div>
	</div>
@yield('serviceload','')
@stop