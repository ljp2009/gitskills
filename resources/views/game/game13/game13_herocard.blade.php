<?php 
    $width = $imgsz[0]; $height = $imgsz[1];
    // if(isset($scale)){
    // 	$width = intval($width * $scale);
    // 	$height = intval($height * $scale);
    // }
    // $imgw = $imgsz[0]; $imgh = $imgsz[1];
    // $szbase = $width / $height;
    // $imgbase = $imgw / $imgh;
    // $marginTop = 0; $marginLeft = 0;
    // if($imgbase > $szbase){
    // 	$flscale = $height / $imgh;
    // 	$imgh = $height;
    // 	$imgw = intval($imgw * $flscale);
    // 	$marginLeft = -1 * intval(($imgw - $width) /2);
    // }else{
    // 	$flscale = $width / $imgw;
    // 	$imgw = $width;
    // 	$imgh = intval($imgh * $flscale);
    // 	$marginTop = -1 * intval(($imgh - $height) /2);
    // }
    // style="margin-left:{{$marginLeft}}px; margin-top:{{$marginTop}}px"
?>
<div class="game13_hero_card" style="width:{{$width}}px; height:{{$height}}px;">
	<img src="{{$heropic}}"  class="game13_hero_card_pic" /> 
	<img class="game13_hero_card_front" src="/game13/pic/front_hero_card/{{$level}}" width={{$width}} height={{$height}} style="margin-top:-{{$height}}px"/>
</div>
