@extends('admins.layouts.base')
@section('domcontent')
<link rel="stylesheet" href="/css/admin/admin.css">
<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong>有妹社区</strong> <small>后台管理</small>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span>{{Auth::user()->display_name}}<span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="/auth/logout"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
    </ul>
  </div>
</header>
<div class="am-cf admin-main">
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="/admin"><span class="am-icon-home"></span> 首页</a></li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav1'}"><span class="am-icon-file"></span> 推荐管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav1">
            <li><a href="/admin/rc/banner-list" class="am-cf"><span class="am-icon-sun-o"></span>封面推荐<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/rc/batch-list" class="am-cf"><span class="am-icon-sun-o"></span>有妹推荐<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/act/list" class="am-cf"><span class="am-icon-sun-o"></span>活动管理<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/rc/master" class="am-cf"><span class="am-icon-sun-o"></span>达人推荐<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/rc/dimension" class="am-cf"><span class="am-icon-sun-o"></span>次元推荐<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/sp/list" class="am-cf"><span class="am-icon-sun-o"></span>专辑推荐<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
          </ul>
        </li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav2'}"><span class="am-icon-file"></span> 数据管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav2">
            <li><a href="/admin/ip/list" class="am-cf"><span class="am-icon-sun-o"></span> IP管理<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/user/list" class="am-cf"><span class="am-icon-sun-o"></span> 用户管理<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/tk/list/0" class="am-cf"><span class="am-icon-sun-o"></span>任务管理<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/dc/list" class="am-cf"><span class="am-icon-sun-o"></span> 次元管理<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/ck/prod" class="am-cf"><span class="am-icon-sun-o"></span> 作品审核<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
          </ul>
        </li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav3'}"><span class="am-icon-file"></span> 资源管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav3">
            <li><a href="/admin/ctrl/qr-code" class="am-cf"><span class="am-icon-sun-o"></span> 二维码生成<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/ip/batch-update" class="am-cf"><span class="am-icon-sun-o"></span> 批量更新属性<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/res/def-image" class="am-cf"><span class="am-icon-sun-o"></span> 系统默认图片<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/schedule/index" class="am-cf"><span class="am-icon-sun-o"></span>任务控制<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/ctrl/tags" class="am-cf"><span class="am-icon-sun-o"></span>标签库<span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
          </ul>
        </li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav4'}"><span class="am-icon-file"></span> 数据统计 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav4">
            <li><a href="/admin/st/" class="am-cf"><span class="am-icon-sun-o"></span> 用户会话统计 <span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="/admin/st/" class="am-cf"><span class="am-icon-sun-o"></span> 用户量统计 <span class="am-icon-star-o am-fr am-margin-right admin-icon-yellow"></span></a></li>
          </ul>
        </li>
        <li><a href="/auth/logout"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p>
        </div>
      </div>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-tag"></span> wiki</p>
          <p>Welcome to the Amaze UI wiki!</p>
        </div>
      </div>
    </div>
  </div>
  <!-- sidebar end -->
<!-- content start -->
@yield('detailcontent')
<!-- content end -->

</div>

<a href="#" class="am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}">
  <span class="am-icon-btn am-icon-th-list"></span>
</a>

<footer>
  <hr>
  <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
</footer>
</div>
@stop
