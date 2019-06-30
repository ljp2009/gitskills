<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="robots" content="noindex, nofollow, nosnippet, noarchive"/>
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <title>Umeiii后台管理</title>

  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>


  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileColor" content="#0e90d2">

  <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.5.2/css/amazeui.min.css">
  <link rel="stylesheet" href="/assets/ymfont/iconfont.css">
</head>
<body>
@yield('domcontent','')
<!--在这里编写你的代码-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.min.js"></script>
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.widgets.helper.min.js"></script>
@yield('jscontent','')
</body>
</html>
