@foreach($items as $item)
<?php if (is_array($item)) {
    $obj = $item['obj'];
} else {
    $obj = $item;
} ?>
<div class="ym_ip_content have_border">
    <div class="ym_ip_discinfo">
        <img class="ym_ip_discinfo_avatar" src={{$obj->user->avatar->getPath(2,'80w_80h_1e_1c')}} onclick="$.ymFunc.goTo('{{$obj->user->homeUrl}}')" />
        <label class="ym_ip_discinfo_title">
            {{$obj->title}}
        </label>
        <label class="ym_ip_discinfo_user">{{$obj->user->display_name}} 发布于 {{$obj->created_at}}</label>
        <div class="ym_ip_discinfo_text">{{$obj->getShotIntro(100)}}......</div>
        <a href="{{$obj->detailUrl}}">查看详情&nbsp;<i class="ymicon-right" style="font-size:7px"></i></a>
    </div>
</div>
@endforeach
