<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="format-detection" content="telephone=no" />
  <title>
  	天下第一武道会
  </title>
  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <link rel="icon" type="/image/png" href="/assets/i/favicon.png">

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="/assets/i/app-icon72x72@2x.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
  <link rel="apple-touch-icon-precomposed" href="/assets/i/app-icon72x72@2x.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="/assets/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileColor" content="#0e90d2">

  <link rel="stylesheet" href="/assets/css/amazeui.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <link rel="stylesheet" href="/css/youmei2.css">
  <link rel="stylesheet" type="text/css" href="/css/youmei_preload.css">
  <style type="text/css">
    @media all and (orientation:landscape) { 
		body { 
		   background:url(/game13/pic/background/landscape);
		   filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		   -moz-background-size:100% 100%;
		   background-size:100% 100%;		 
		} 

		.popup_editor_bg{
			 background:url(/game13/pic/background/landscape);
		}
	} 

    @media all and (orientation:portrait) { 
		body { 
		   background:url(/game13/pic/background/portrait);
		   filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		   -moz-background-size:100% 100%;
		   background-size:100% 100%;		 
		} 
		.popup_editor_bg{
			 background:url(/game13/pic/background/portrait);
		}
	} 
   	  .userinfo{
		position:fixed;
		z-index: 99;
		margin:10px;
   	  }

   	  .usercards{
		position:fixed;
		z-index: 99;
		margin:10px;
 
   	  }

   	  .userheros{
		position:fixed;
		z-index: 99;
		margin:10px;
 
   	  }

   	  .userrunes{
		position:fixed;
		z-index: 99;
		margin:10px;
 
   	  }

   	  .topcards{
		position:fixed;
		z-index: 99;
		margin:10px;
 
   	  }

   	  .useractions{
		position:fixed;
		z-index: 99;
		margin:10px;
   	  }

	  .mask{
		position:absolute;
		z-index: 100;
		left:0px;
		top:0px;	  	
	  	width:100%;
	  	height:100%;
	  	background:#888;
		filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		-moz-background-size:100% 100%;
		background-size:100% 100%;	
		display:none;
    	filter:alpha(opacity=60);  
        -moz-opacity:0.6;  
        -khtml-opacity: 0.6;  
        opacity: 0.6;  
   	  }

   	  .popup_editor{
		position:absolute;
		z-index: 101;
		display:none;
		border-radius: 10px;
		border:solid #666 2px;
		filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		-moz-background-size:100% 100%;
		background-size:100% 100%;
		overflow: hidden;
   	  }

   	  .game13_userhead{
   	  	margin:10px; 
   	  	width:174px; 
   	  	height:168px; 
   	  	border-radius:50%; 
   	  	background:url(/game13/pic/userhead);
		filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		-moz-background-size:100% 100%;
		background-size:100% 100%;
   	  }

   	  .entry_userinfo_right{
   	  	float:right;
   	  	margin:10px;
   	  }
  </style>
</head>
<body>
	<button id="displayFuwen">Display</button>
</body>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/js/ym_preload.js"></script>
<script type="text/javascript">
var ym_game13_mask = function(){
	var MASK = this;
	MASK.html = '<div class="mask"></div>';

	MASK.init = function(){
		MASK.mask = $('.mask');
		if(MASK.mask.length == 0){
			$(document.body).append(MASK.html);
			MASK.mask = $('.mask');
		}

	};

	MASK.init();

	MASK.setVisible = function(visible){
		if(typeof(visible)=='undefined'){
			visible = true;
		}
		if(visible){
			MASK.mask.css('display', 'block');
		}else{
			MASK.mask.css('display', 'none');
		}
	}
};

var YM_GAME13_MASK = new ym_game13_mask();


var ym_game13_popup_editor = function(id){
	var ED = this;

	ED.html = '<div class="popup_editor popup_editor_bg" id="{id}">' + 
		'<a href="javascript: void(0)" id="{id}_close" class="am-close am-close-spin" data-am-modal-close>&times;</a>'+
	'</div>';


	ED.mask = YM_GAME13_MASK;
	ED.margin = 10;
	ED.id = id;
	ED.editor = false;

	ED.init = function(){
		ED.editor = $('#' + id);
		if(ED.editor.length == 0){
			var reg = new RegExp('{id}', 'g');
			ED.html = ED.html.replace(reg, id);
			$(document.body).append(ED.html);
			ED.editor = $('#' + id);
			$('#' + id + '_close').on('click', function(){
				ED.hide();
			});
		}
	};

	ED.injectHTML = function(html){
		ED.editor.append(html);
	};

	ED.popup = function(){
		ED.mask.setVisible(true);
		var top =  ED.margin;
		var left = ED.margin;
		ED.editor.css({left:left+"px", top:top +"px"});
		ED.editor.css({width:'10px', height:'10px', display:'block'});

		ED.resize(true);
	};

	ED.hide = function(){
		ED.editor.animate({width:"10px", height:"10px"}, 300, function(){
			ED.editor.css('display', 'none');
			ED.mask.setVisible(false);
		});
	};
	
	ED.resize = function(animate){
		ED.screenW = $(document.body).width();
		ED.screenH = $(document.body).height();
		var w = ED.screenW - 2 * ED.margin;
		var h = ED.screenH - 2 * ED.margin;
		if(typeof(animate)=='undefined'){
			animate = false;
		}

		if(animate){
			ED.editor.animate({width:w +"px", height:h + "px"}, 300);
		}else{
			ED.editor.css({width:w +"px", height:h + "px"});
		}
	};

	ED.init();	
};

var ym_game13_fuwen_editor = function(){
	var FW = this;

	FW.editor = new ym_game13_popup_editor('fuwen');

	FW.popup = function(){
		FW.editor.popup();
	};

	FW.hide = function(){
		FW.editor.hide();
	};

	FW.resize= function(){
		FW.editor.resize();
	};

};

var ym_game13_entry = function(){
	var ENT = this;

	ENT.fuwenEditor = false;

	ENT.preload = new ym_preload(); //rely on ym_preload.js

	ENT.init = function(){
		ENT.fuwenEditor = new ym_game13_fuwen_editor();
		ENT.checkOrientation();

		ENT.preload.startLoad({res:{}, img:'/game13/pic/preloadPic'}, function(){
			// ENT.initUI();
		});

		$('#shut_fuwen').on('click', function(){
			ENT.hideFuwenDialog();
		});

		ENT.loadData();
		window.addEventListener("orientationchange", function() {
			ENT.checkOrientation();

		}, false);
	};

	ENT.checkOrientation = function(){
		ENT.screenW = $(document.body).width();
		ENT.screenH = $(document.body).height();
		if(ENT.screenW < ENT.screenH){
			ENT.orientation = "portrait";
		}else{
			ENT.orientation = "landscape";
		}
		ENT.fuwenEditor.resize();
	};

	ENT.__handleResources = function(result){

	};

	ENT.loadUserInfo = function(){
		$.get('/game13/view/entry/userinfo', function(result){
			$('.userinfo').append(result);
		});
	};

	ENT.loadData = function(){
		$.getJSON("/game13/loadEntryData", function(result){
			ENT.preload.setPreProgress(0.1);
			//PREPARE data
			var res = ENT.__handleResources(result);

			ENT.initUI(); //NEED to be moved 
			ENT.loadUserInfo();
			// ENT.preload.setResources(res);
		});
	};

	ENT.initUI = function(){
		var html = '<div class="userinfo"></div>' + 
					'<div class="usercards"></div>' + 
					'<div class="userheros"></div>' + 
					'<div class="userrunes"></div>' + 
					'<div class="topcards"></div>' + 
					'<div class="useractions"></div>';
		$(document.body).append(html);
	};

	ENT.initPortraitUI = function(){

	};

	ENT.initLandscapeUI = function(){

	};




	ENT.displayFuwenDialog = function(){
		ENT.fuwenEditor.popup();
	};

	ENT.hideFuwenDialog = function(){
		ENT.fuwenEditor.hide();
	};

};

var initall = function(){
	var entry = new ym_game13_entry();
	entry.init();

	$('#displayFuwen').on('click', function(){
		entry.displayFuwenDialog();
	});
};

$(function(){
	document.body.onload = initall;
});

</script>
</html>