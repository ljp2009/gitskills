<style type="text/css">

</style>
<div class="am-slider am-slider-default am-my-slider" data-am-flexslider id="demo-slider-0">
    <ul class="am-slides">
        @if(isset($list) && count($list)>0)
        @foreach($list as $banner)
        <li onclick="window.location = '{{$banner->url}}'">
            <img src="{{$banner->imagePath}}@290w_300h_4e_204-204-204bgc" />
            <h3>{{$banner->description}}</h3>
        </li>
        @endforeach
        @else
        <li style="text-align:center;">
            <h3>暂无推荐</h3>
        </li>
        @endif
    </ul>
</div>
