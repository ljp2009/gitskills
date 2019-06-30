<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="format-detection" content="telephone=no" />
  <title>
       @yield('title','有妹社区')
  </title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
  <link rel="apple-touch-icon-precomposed" href="/assets/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileImage" content="/assets/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileColor" content="#0e90d2">

  <link rel="stylesheet" href="/assets/css/amazeui.min.css?a=1">
  <link rel="stylesheet" href="/assets/css/app.css">
  <link rel="stylesheet" href="/assets/ymfont/iconfont.css">
  <!-- <link rel="stylesheet" href="/css/youmei2.css"> -->
  <link rel="stylesheet" href="/css/common.css?a=2">
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="/assets/js/jquery.min.js"></script>
	<script src="/js/common.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->
  @yield('head')
</head>
<body>
    <div class="ym_cm_preview">
        <div class="ym_cm_preview_position">
            <div class="ym_cm_preview_container">
            <img class="ym_cm_preview_image" />
            <label class="ym_cm_preview_label"></label>
            </div>
        </div>
        <div class="ym_cm_preview_shade"></div>
    </div>
<div style="width:100%;max-width:1140px;margin-left:auto;margin-right:auto;border-left:solid 1px #e2e2e2;border-right:solid 1px #e2e2e2;">
  @yield('content')
<div>
<div class="ym-footer">有妹社区&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;沪ICP备16019901号-1 </div>
</body>
</html>
