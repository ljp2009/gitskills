@extends('layouts.block')

@section('title','分类')
@section('content')
	@section('serverload')
	@include('partview.headerbar',['left'=>'backTo', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'分类', 'backUrl'=>'/reshall'])
	<link href="/css/ym_classify.css" rel="stylesheet"/>
	<!-- 榜单 分类  达人 集市 顶部导航 -->
    @include('partview.quicknav',['active'=>'classify'])

  	<div class="ym_classify">
    	<div class = "ym_classify_container">
	    	<span class="ym_classify_title">动漫</span>
	    	<ul>
	    		@foreach( $cartoons as $value)
				  <li onclick="$.ymFunc.goTo('/classify/cartoon/list/{{trim($value->name)}}/0')">
				  	<span>{{trim($value->name)}}</span>
				  </li>
				@endforeach

			</ul>
    	</div>
	</div>
	<div class="ym_classify">
	    <div class = "ym_classify_container">
	    	<span class="ym_classify_title">游戏</span>
	    	<ul>
	    		@foreach( $games as $value)
				  <li onclick="$.ymFunc.goTo('/classify/game/list/{{trim($value->name)}}/0')">
				  	<span>{{trim($value->name)}}</span>
				  </li>
				@endforeach

			</ul>
	    </div>
	</div>
	<div class="ym_classify">
	    <div class = "ym_classify_container">
	    	<span class="ym_classify_title">小说</span>
	    	<ul>
	    		@foreach( $storys as $value)
				  <li onclick="$.ymFunc.goTo('/classify/story/list/{{trim($value->name)}}/0')">
				  	<span>{{trim($value->name)}}</span>
				  </li>
				@endforeach

			</ul>
	    </div>
	</div>
	<div class="ym_classify">
	    <div class = "ym_classify_container">
	    	<span class="ym_classify_title">轻小说</span>
	    	<ul>
	    		@foreach( $lights as $value)
				  <li onclick="$.ymFunc.goTo('/classify/light/list/{{trim($value->name)}}/0')">
				  	<span>{{trim($value->name)}}</span>
				  </li>
				@endforeach

			</ul>
	    </div>
	</div>
	
  @stop
  @parent
@stop
@section('runScript')
<script type="text/javascript">
</script>
@stop
