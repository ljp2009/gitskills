<div class="ym_cm_card">
        <ul class="am-avg-sm-4 ym_quicknav">
            <li class="{{$active=='ranking'?'ym_active':''}}">
                <i class="ymicon-statistics" onclick="$.ymFunc.goTo('/ranking')"></i>
                <span onclick="$.ymFunc.goTo('/ranking')">榜单</span>
            </li>
            <li class="{{$active=='classify'?'ym_active':''}}">
                <i class="ymicon-app" onclick="$.ymFunc.goTo('/classify')"></i>
                <span>分类</span>
            </li>
            <li class="{{$active=='master'?'ym_active':''}}">
                <i class="ymicon-star" onclick="$.ymFunc.goTo('/ranking/list/user/0')"></i>
                <span onclick="$.ymFunc.goTo('/ranking/list/user/0')">达人</span>
            </li>
            <li class="{{$active=='market'?'ym_active':''}}">
                <i class="ymicon-market"></i>
                <span onclick="$.ymFunc.goTo('/building')">集市</span>
            </li>
        </ul>
</div>
