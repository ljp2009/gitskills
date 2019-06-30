@extends('layouts.block')


@section('content')
<?php

?>

<div id="audioask"></div>
<button onclick="doPlay()">Play</button>
<button onclick="doStop()">Stop</button>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>

<script type="text/javascript">
	var audioId= "_audio";
	var audiosrc = "/pic/local/cards/test.mp3";
	$(function(){
		$('#audioask').html('<audio id="_audio">您的浏览器不支持 audio 标签。</audio>');
		$('#_audio').on('canplaythrough', function(){
			alert('Loaded');
		});
		$('#_audio').attr('src', audiosrc);
	});

	function doPlay(){
		$('#_audio')[0].load();
		
		$('#_audio')[0].play();
	};

	function doStop(){
		$('#_audio')[0].pause();
	};
</script>
@stop
