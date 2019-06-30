<?php
/* 页面切换栏
 * list: 切换列表
 * active：当前活动页面
* */
 ?>
<div class="ym_tabswitchbar" style="{{isset($inPage)?'position:relative;margin:0;top:0;background:none':''}}">
<ul class="ym_avg_{{count($list)}}">
@foreach($list as $key=>$value)
    @if($key == $active)
        <li class="ym_active"> {{$value['name']}} </li>
    @else
        <li onclick="$.ymFunc.goTo('{{$value['url']}}')"> {{$value['name']}} </li>
    @endif
@endforeach
</ul>
</div>
