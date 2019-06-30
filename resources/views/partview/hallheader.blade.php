<header data-am-widget="header"
    class="am-header am-header-default" style="padding: 0">
    <div class="am-header-left am-header-nav" >
        <a href="#left-menu-user" class=""
            data-am-offcanvas="{target: '#left-menu-user'}" style="margin: 0;padding: 0">
            <!-- <img src="imgs/headers/default.jpg" class="am-circle"
            style="height: 2.8rem;margin: -0.2rem 0 0 0;border: solid 1px rgb(255,221,21)" />  -->
            <i class="am-icon-user am-icon-md ym-icon-menu" style="@if(Auth::check()&&Auth::user()->newLetterNum&&Auth::user()->newLetterNum->count()) background:url(../imgs/am-menu-new.png) no-repeat center center / 100%; @endif"></i>
        </a>
    </div>
    <h1 class="am-header-title" style="padding: 0">
    <img src="/imgs/logo.png" style="height: 3.5rem;margin: 0.3rem 0 0 0" />
    <label id="ff"></label>
    </h1>
    <div class="am-header-right am-header-nav">
        <a href="javascript:void(0)" class="" onclick="quickSearch()">
            <i class="am-header-icon am-icon-search am-icon-sm ym-icon-search" style="margin-top: 0.2 rem;"></i>
        </a>
    </div>
</header>

<div class="am-modal am-modal-prompt" tabindex="-1" id="my-search">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">作品快速查询</div>
    <div class="am-modal-bd">
      <input type="text" class="am-modal-prompt-input">
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>查询</span>
    </div>
  </div>
</div>
<script type="text/javascript">
    function quickSearch(){
        $('#my-search').modal({
            onConfirm: function(e) {
                window.location = '/search/list/'+e.data+"/0";
            },
            onCancel: function(e) {

      }});
    }
</script>
<nav data-am-widget="menu" class="am-menu  am-menu-offcanvas1" >
    <div id="left-menu-user" class="am-offcanvas" >
        <div class="am-offcanvas-bar">
            <ul class="am-menu-nav am-avg-sm-1">
                <li class="my-info-header">
                @if(!Auth::check())
                    <div align="center" style="height: 19rem;padding: 2rem 1rem 2rem 0;color: #fff;">
                        <img class="am-img-thumbnail am-circle am-photo"
                        src="/imgs/headers/default.jpg"
                        width="80" height="80" />
                        <div style="padding: 0 1rem">
                            <h6>未登录</h6>
                        </div>
                        <div>
                            <span class="am-badge am-radius" style="background:#ffdd15; color:#000"
                            onclick ="window.location='/auth/login'">登录</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="am-badge am-radius" style="background:#ffdd15; color:#000"
                            onclick ="window.location='/auth/login'">注册</span>
                        </div>
                    </div>
                @else
                    <div align="center" style="height: 19rem;padding: 2rem 1rem 2rem 0;color: #fff;">
                        <button type="button" class="am-btn am-btn-default am-radius am-btn-xs ym-btn-nopad am-btn-left">关注<br>
                        <span class="am-num">{{Auth::user()->followNum}}</span></button>
                        <img class="am-img-thumbnail am-circle am-photo"
                        src="{{Auth::user()->midAvatar}}"
                        width="80" height="80" />
                        <button type="button" class="am-btn am-btn-default am-radius am-btn-xs ym-btn-nopad am-btn-right">粉丝<br>
                        <span class="am-num">{{Auth::user()->fansNum}}</span></button>
                        <div style="padding: 0 1rem">
                            <h6>{{Auth::user()->display_name}}</h6>
                            <p style="font-size:1rem;margin:0.5rem 0 0.9rem;">{{Auth::user()->label}}</p>
                        </div>
                    </div>
                @endif
                </li>
                @if(Auth::check())
                <li class="">
                    <a href="##" class="" >钱包</a>
                </li>
                <li class="">
                    <a href="/private/list/default/0/{{Auth::user()->id}}" class="" >我的信件
                    @if(Auth::user()->newLetterNum->count()>0)
                    	<span class="am-badge am-badge-danger am-round" style="position:absolute;top:50%;right:0%;text-indent:0;margin-top:-9px;">{{Auth::user()->newLetterNum->count()}}</span>
                    @endif
                    </a>
                </li>
                <li class="">
                    <a href="##" class="" >我的浏览</a>
                </li>
                <li class="">
                    <a href="##" class="" >我的统计</a>
                </li>
                <li class="">
                    <a href="##" class="" >我的评论</a>
                </li>
                <li class="">
                    <a href="##" class="" >我的众筹</a>
                </li>
                <li class="am-parent">
                    <a href="##" class="" >我的任务</a>
                    <ul class="am-menu-sub am-collapse  am-avg-sm-3 ">
                        <li class="">
                            <a href="/task/list/usercreaterequest/0/{{ Auth::user()->id}}" class="" >-发布的任务</a>
                        </li>
                        <li class="">
                            <a href="/task/list/userjoinrequest/0/{{ Auth::user()->id}}" class="" >-接收的任务</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="/home/list/works/0/{{Auth::user()->id}}" class="" >我的作品</a>
                </li>
                <li class="am-parent">
                    <a href="##" class="" >我的店铺</a>
                    <ul class="am-menu-sub am-collapse  am-avg-sm-3 ">
                        <li class="">
                            <a href="##" class="" >创意出售</a>
                        </li>
                        <li class="">
                            <a href="##" class="" >周边售卖</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="##" class="" >我的活动</a>
                </li>
                <li class="">
                    <a href="/dimension/list/default/0/{{Auth::user()->id}}" class="" >我的次元</a>
                </li>
                <li class="">
                    <a href="##" class="" >签到打卡</a>
                </li>
                <li class="">
                    <a href="##" class="" >经纪人</a>
                </li>
                <li class="">
                    <a href="/certification/list/0" class="" >认证申请</a>
                </li>
                <li class="">
                    <a href="##" class="" >我的订单</a>
                </li>
                <li class="">
                    <a href="/user/showuserinfo/{{Auth::user()->id}}" class="" >个人设置</a>
                </li>
                <li class="" style="display:none">
                    <a href="/auth/changepassword" class="" >修改密码</a>
                </li>
                <li class="">
                    <a href="/auth/logout" class="" >退出登录</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
