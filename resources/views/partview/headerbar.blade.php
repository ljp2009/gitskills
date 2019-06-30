<?php
/* 顶栏
 * 页面必填参数：
 * left: back, backTo, user,none(不显示)
 * right: search, home,post, none(不显示)
 * center: pageTitle, logo(点击返回主页)
 * 页面可选参数:
 * backUrl：当left=backTo的时候，将返回这个路径, 当这个路径未设置的时候，则执行后退操作
 * backText: 当left=backTo的时候，back按钮现在这个文字，如果这个文字未设置则显示返回
 *
 * searchUrl:当right=search的时候，搜索框的内容将被post到这个地址,当未设置的时候post到"/search"
 * searchText：搜索框的placeholder，为设置的时候显示“请输入要搜索的关键字”
 *
 * homeUrl：当right=home的时候，跳转到这个地址，如果未设置，则跳转到“/reshall”
 * pageTitle: 当center=pageTitle的时候，headerbar显示这个文字, 默认为“”
 * 页面方法
 * */
?>
<div class="ym_headerbar">
    <ul class="am-avg-sm-3">
        <!--左侧按钮-->
        <li class="ym_headerbar_left">
        @if(!isset($left) || $left == 'back')
            <i class="ymicon-left" style="font-size:14px" onclick="$.ymFunc.back('')"></i>
            <span class="ym_backheader_btn" onclick="$.ymFunc.back('')">{{isset($backText)?$backText:'返回'}}</span>
        @elseif($left == 'backTo')
            <i class="ymicon-left" style="font-size:14px" onclick="$.ymFunc.back('{{isset($backUrl)?$backUrl:''}}')"></i>
            <span class="ym_backheader_btn" onclick="$.ymFunc.back('{{isset($backUrl)?$backUrl:''}}')">
                {{isset($backText)?$backText:'返回'}}</span>
        @elseif($left == 'post')
            <i class="ymicon-left" style="font-size:14px" onclick="{{$backFunc}}"></i>
            <span class="ym_backheader_btn" onclick="{{$backFunc}}">
                {{isset($backText)?$backText:'返回'}}</span>
        @elseif($left == 'user')
            <i class="ymicon-user2" onclick="{{Auth::check()?'$.ymSideBar.show()':'$.ymFunc.goLogin()'}}"></i>
            &nbsp;
        @else
            <span class="ym_backheader_btn">&nbsp;</span>
        @endif
        </li>
        <!--中间logo-->
        <li class="ym_headerbar_center">
        @if(!isset($center) || $center == 'logo')
            <img class="ym_headerbar_logo" src="/imgs/newlogo.png" onclick="$.ymFunc.goHome()" />
        @elseif($center == 'pageTitle')
            <span class="ym_headerbar_title">{{$pageTitle}}</span>
        @endif
        </li>
        <!--右侧按钮-->
        <li class="ym_headerbar_right">
        @if(!isset($right) || $center == 'none')
            &nbsp;
        @elseif($right == 'search')
            <i class="ymicon-search" onclick="$.ymSearchBar.show()"></i>
        @elseif($right == 'home')
            <i class="ymicon-home" onclick="$.ymFunc.goHome()"></i>
        @elseif($right == 'post')
            <span class="ym_backheader_btn" onclick="{{$postFunc or 'postForm()'}}" >
                &nbsp;{{isset($postText)?$postText:'发布'}}</span>
            <i class="ymicon-right" style="font-size:14px" onclick="{{$postFunc or 'postForm()'}}"></i>
        @elseif(isset($right) && is_array($right))
        <div class="am-dropdown" data-am-dropdown>
            <i class="am-header-icon am-icon-wrench am-dropdown-toggle" data-am-dropdown-toggle style='font-weight:100'></i>
          <ul class="am-dropdown-content">
            @foreach($right as $itemName => $itemAction)
            @if($itemName=='-' || $itemName == '--')
            <li class="am-divider"></li>
            @else
            <li><a href="javascript:void(0)" onclick="{{$itemAction}}">{{$itemName}}</a></li>
            @endif
            @endforeach
          </ul>
        </div>
        @endif
            <i class="ymicon-close" style="display:none" onclick="$.ymShade.hide()"></i>
        </li>
    </ul>
</div>
<div style="height:44px;width:100%;">&nbsp;</div>
<div class="ym_shade" onclick="$.ymShade.hide()"></div>
@if($left == 'user' && Auth::check())
<div class="ym_sidebar">
    <div class="ym_sidebar_user">
        <div class="ym_sidebar_user_top"><i class="ymicon-close" onclick="$.ymShade.hide()"></i></div>
        <div class="ym_sidebar_user_header">
             <img class="ym_sidebar_user_avatar am-circle" src="{{Auth::user()->avatar->getPath(2, '128w_128h_1e_1c')}}"
                onclick="$.ymFunc.goTo('/home/list/default/0/{{Auth::user()->id}}')" />
            <div class="ym_sidebar_user_name">{{Auth::user()->display_name}}</div>
        </div>
        <div class="ym_sidebar_user_coin">金币：{{Auth::user()->gold}}</div>
        <div class="ym_sidebar_user_info" onclick="$.ymFunc.goTo('/user/list/follow/0/{{Auth::user()->id}}')"
             style="float:left;border-right:solid 1px #565656;">
           <span class="ym_sidebar_user_number">{{Auth::user()->followNum}}</span><br />
           <span class="ym_sidebar_user_text">关注</span>
        </div>
        <div class="ym_sidebar_user_info" onclick="$.ymFunc.goTo('/user/list/fans/0/{{Auth::user()->id}}')"
             style="float:right">
           <span class="ym_sidebar_user_number">{{Auth::user()->fansNum}}</span><br />
           <span class="ym_sidebar_user_text">粉丝</span>
        </div>
    </div>
    <ul class="ym_sidebar_list">
        <li >
            <div class="ym_sidebar_item" id="sign_in_item">
                <i class="ymicon-t-finish"></i>&nbsp;&nbsp;签到
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/private/list/default/0/{{Auth::user()->id}}')">
            <div class="ym_sidebar_item">
                <i class="ymicon-msg"></i>&nbsp;&nbsp;消息提醒
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/myhistory/list/default/0')">
            <div class="ym_sidebar_item">
                <i class="ymicon-eye"></i>&nbsp;&nbsp;浏览历史纪录
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/userfollow/list/user-production/0')">
            <div class="ym_sidebar_item">
                <i class="ymicon-heart-o"></i>&nbsp;&nbsp;我的关注
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/usertask')">
            <div class="ym_sidebar_item">
                <i class="ymicon-bag-o"></i>&nbsp;&nbsp;我的任务
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/mymarket')" style="display:none" >
            <div class="ym_sidebar_item">
                <i class="ymicon-market"></i>&nbsp;&nbsp;我的店铺
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/uset/main')">
            <div class="ym_sidebar_item">
                <i class="ymicon-setting"></i>&nbsp;&nbsp;账户设置
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
        <li onclick="$.ymSideBar.itemOnClick(this,'/auth/logout')">
            <div class="ym_sidebar_item">
                <i class="ymicon-logout"></i>&nbsp;&nbsp;退出
                <i class="ym-newicon-right ym_sidebar_goto_icon"></i>
            </div>
        </li>
    </ul>
</div>
@endif
@if($right=='search')
<div class="ym_searchbar">
    <form action="{{isset($searchUrl)?$searchUrl:'/search'}}" method="post" onsubmit="return $.ymSearchBar.search();">
    <input name="_token" type="hidden" value="{{csrf_token()}}" />
    <input name="keyword" class="ym_searchbar_input" type="search" placeholder="{{isset($searchText)?$searchText:'  输入需要搜索的关键词。'}}" />
    </form>
</div>
@endif

