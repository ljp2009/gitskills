<?php
/*
 * 底部菜单栏
 * page: 'main','task','game','home' 当前激活的页面
 * addFuncs: ['name'=>'action'], 添加按钮的操作，数组，key: 显示文字; value: 操作地址(get),数组为空的时候不显示
 * addTitle: 显示在添加框上的标题
 * */
?>
<div class="ym_footerbar">
    <ul class="ym_avg_5">
@if($page=='main')
        <li class="am-active">
            <a href="javascript:void(0)">
                <i class="ymicon-home"></i> <br /> <span>首页</span>
            </a>
        </li>
@else
        <li class="">
            <a href="/reshall">
                <i class="ymicon-home-o"></i> <br /> <span>首页</span>
            </a>
        </li>
@endif
@if($page=='task')
        <li class="am-active">
            <a href="javascript:void(0)">
                <i class="ymicon-bag"></i> <br /> <span>任务</span>
            </a>
        </li>
@else
        <li class="">
            <a href="/taskhall">
                <i class="ymicon-bag-o"></i> <br /> <span>任务</span>
            </a>
        </li>
@endif
        <li class="">
            <a href="javascript:void(0)">
                <span type="button" class="ym_footerbar_addbtn" 
                    
                ><i class="ymicon-add"></i></span>
            </a>
        </li>
@if($page=='game')
        <li class="am-active">
            <a href="/building">
                <i class="ymicon-puzzle"></i> <br /> <span>MOD</span>
            </a>
        </li>
@else
        <li class="">
            <a href="/building">
                <i class="ymicon-puzzle-o"></i> <br /> <span>MOD</span>
            </a>
        </li>
@endif
@if($page=='home')
        <li class="am-active">
            <a href="javascript:void(0)">
                <i class="ymicon-user"></i> <br /> <span>我家</span>
            </a>
        </li>
@else
        <li class="">
            <a href="{{Auth::check()?Auth::user()->homeUrl:'/auth/login' }}">
                <i class="ymicon-user-o"></i> <br /> <span>我家</span>
            </a>
        </li>
@endif
    </ul>
</div>
@if(isset($addFuncs))
    @include('partview.addpanel')
@endif
