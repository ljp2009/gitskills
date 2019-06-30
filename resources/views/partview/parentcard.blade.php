@if(!empty($relItem['url']))
<div class="ym-header-relatecard"  onclick="window.location = '{{$relItem['url']}}'">
@else
<div class="ym-header-relatecard">
@endif
@if(!empty($relItem['imgPath']))
    <img src="{{$relItem['imgPath']->getpath(1,'128w_128h_1e_1c')}}" class="ym-header-relatecard-img" />
@endif
    <div class="ym-header-relatecard-info">
       <span class="ym-header-relatecard-title">{{$relItem['title']}}</span>
        @if(!empty($relItem['text']))
       <span class="ym-header-relatecard-text">{{$relItem['text']}}</span>
        @endif
    </div>
</div>
