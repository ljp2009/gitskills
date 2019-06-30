<?php 
    $width = $imgsz[0];
    $height = $imgsz[1];
    if (isset($scale)) {
        $width = intval($width * $scale);
        $height = intval($height * $scale);
    }
?>
<div class="game13_userhead" style='background:url({{$headimg}});width:{{$width}}px; height:{{$height}}px;filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";-moz-background-size:100% 100%;background-size:100% 100%;'>
	<img src="/game13/pic/user_head_cover" class="game13_userhead_pic"/>
</div>