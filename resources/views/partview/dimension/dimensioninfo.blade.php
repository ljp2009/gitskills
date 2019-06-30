<link rel="stylesheet" href="/css/ym_dimension.css" />
<div class="ym-dim-header">
<div class="ym-dim-header-line"></div>
        <img src="{{$models->header}}@72w_72h_1e_1c" alt="" class="ym-dim-header-img am-radius am-img-thumbnail">
    <div class="ym-dim-header-info">
        <span class='ym-dim-header-title'>{{ $models->name }}</span>
        <span class='ym-dim-header-desc'>{{ $models->text }}</span>
    </div>
</div>
<div class="ym-dim-header-bar">
    <div class="ym-dim-header-alert-right">入驻：{{$models->enterSumValue}}&nbsp;&nbsp;&nbsp;&nbsp;帖子：{{$models->publishSumValue}}</div>
    <div class="ym-dim-header-alert-left"><i class="am-icon-volume-up"></i>
    @if(!is_null($models->dimensionEnter) && $models->dimensionEnter->is_enter=='Y')
       你已经入驻这个次元咯！～
    @else
       欢迎加入这个次元！～
    @endif
    </div>
</div>

