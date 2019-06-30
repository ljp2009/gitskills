@extends('layouts.block')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/youmei_preload.css">
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/js/ym_preload.js"></script>
<script type="text/javascript">
function preload(){
	var res = {};

	function addDazhao(idx){
		var nm = 'dazhao_' + idx;
		var src = "/pic/local/cards/" +nm +".gif";
		var newres = {name:nm, type:'img', path:src};
		res[nm] = newres;
	}

	function addCard(cl, idx){
		var nm = cl + '_' + idx;
		var src = "/pic/local/cards/" +nm +".png";
		var newres = {name:nm, type:'img', path:src};
		res[nm] = newres;	
	}

	for(var i=1; i<=6; i++){
		addDazhao(i);
	}

	for(var i=2; i<=14; i++){
		addCard('club', i);
	}

	var preloader = new ym_preload();

	preloader.startLoad({res:{}, img:'/game13/preloadPic'}, function(){});

	setTimeout(function(){
		preloader.setPreProgress(0.2);
		preloader.setResources(res);
	}, 2000);
}

document.body.onload = preload;

</script>
@stop
