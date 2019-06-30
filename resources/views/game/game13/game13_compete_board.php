<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
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
		   overflow:hidden;
		   background:url(/game13/pic/background/landscape);
		   filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		   -moz-background-size:100% 100%;
		   background-size:100% 100%;		 
		} 
	} 

    @media all and (orientation:portrait) { 
		body { 
		   overflow:hidden;
		   background:url(/game13/pic/background/portrait);
		   filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		   -moz-background-size:100% 100%;
		   background-size:100% 100%;		 
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
		position:fixed;
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
		position:fixed;
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
   	  	width:174px; 
   	  	height:168px; 
   	  	border-radius:50%; 
		filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		-moz-background-size:100% 100%;
		background-size:100% 100%;
   	  }

   	  .entry_userinfo_right{
   	  	float:right;
   	  	margin:10px;
   	  }
  	.game13_hero_card_inner{
		overflow:hidden;
  		-webkit-box-shadow: 3px 3px 3px;  
		  -moz-box-shadow: 3px 3px 3px;  
		  box-shadow: 3px 3px 3px;
	   border-radius:5px;
	   -webkit-border-radius:5px;
	   -moz-border-radius:5px;	
  	}
  	.game13_hero_card_front{
  		position:fixed;
  		z-index: 100;
	   border-radius:5px;
	   -webkit-border-radius:5px;
	   -moz-border-radius:5px;	 		  		
  	}	
	.game13_sidebar{
		position:fixed;
		z-index: 99;
	}
	.absolute_pos{
  		position:fixed;
  		z-index: 100;		
	}
	.game13_hero_feature{
  		position:fixed;
  		z-index: 100;	
  		font-size: 14px;
  		font-family:'Courier New, Courier, monospace';	
  		font-weight: bold;
	}
	.game13_hero_feature_blood{
  		position:fixed;
  		z-index: 100;	
  		border:solid #558 1px;
		border-radius:5px;
		-webkit-border-radius:5px;
		-moz-border-radius:5px;
		overflow:hidden;
		background:#666;
	}
	.game13_hero_feature_blood_bar{
		height: 5px;
		width: 85%;
		float: left;
		background-color: #063053;
		/* chrome 2+, safari 4+; multiple color stops */
		background-image:-webkit-gradient(linear, left bottom, left top, color-stop(0.32, #990630), color-stop(0.66, #dd3958), color-stop(0.83, #990631));
		/* chrome 10+, safari 5.1+ */
		background-image: -webkit-linear-gradient(#990630, #dd3958, #990631);
		/* firefox; multiple color stops */
		background-image: -moz-linear-gradient(top,#990630, #dd3958, #990631);
		/* ie 6+ */
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#990630', endColorstr='#dd3958');
		/* ie8 + */
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#990630', endColorstr='#dd3958')";
		/* ie10 */
		background-image: -ms-linear-gradient(#990630, #dd3958, #990631);
		/* opera 11.1 */
		background-image: -o-linear-gradient(#990630, #dd3958, #990631);

		background-image: linear-gradient(#990630, #dd3958, #990631);	

		border-radius:3px;
		-webkit-border-radius:3px;
		-moz-border-radius:3px;		
	}
	#options_area{
		position:fixed;
		z-index: 100;
		overflow-x:auto;
		overflow-y:hidden;
	}

	.option_block{
	   float:left;
	   margin-left:10px;
	   border-radius:5px;
	   -webkit-border-radius:5px;
	   -moz-border-radius:5px;	   
	   width:100px;
	   border:solid 2px #ccc;
	   background:#fff;
	   box-shadow: 0px 0px 10px 10px #eee inset;
	}

	.option_block_inside{
		height:30%;
		font-size: 20px;
		font-weight: bold;
		color:#03c;
		margin-top: 3px;
		margin-left: 15px;
	}

	.option_selected{
		-webkit-animation: option_selected_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: option_selected_anim 1.5s ease-in-out infinite alternate;
		animation: option_selected_anim 1.5s ease-in-out infinite alternate; 
	}

	@-webkit-keyframes option_selected_anim {
		from{
			box-shadow: 0px 0px 5px 5px #faa inset;
		}
		to{
			box-shadow: 0px 0px 10px 10px #fee inset;
		}
	}

	@-moz-keyframes option_selected_anim {
		from{
			box-shadow: 0px 0px 5px 5px #dbb inset;
		}
		to{
			box-shadow: 0px 0px 10px 10px #fee inset;
		}
	}

	.game13_hero_feature_block_container{
		text-shadow: 0 0 3px #f00,
					0 0 5px #fff,
					0 0 7px #ff0;
	}
	#screen_info{
		position:fixed;
		z-index: 101;		
	}

	.console {
		position:fixed;
		z-index: 99;
		margin:5px;
		overflow-x: hidden;
		overflow-y: auto;
		border:solid 2px #aaa;
	    border-radius:5px;
	    -webkit-border-radius:5px;
	    -moz-border-radius:5px;	 		
	    background:rgba(0, 0, 0, 0.4);
	    color:#fff;
	    font-size:12px;
	}

	.console_container{
		margin:5px;
		width:100%;
	}

	.console_copy {
		position:fixed;
		z-index: 100;
	}

	.card_no{
		font-weight: bold;
		color: #fff;
		text-shadow:1px 1px 2px #aaa;
		text-align: center;
	}

	#action_pane{
		position:fixed;
		z-index: 99;
	}

	.round_info{
		filter:alpha(opacity=0);
		-moz-opacity:0;
		-khtml-opacity: 0;
		opacity: 0;
		text-decoration:none; 
	  	font-family: blkbd;
	  	font-size: 50px;
	  	color:#0c3;
		-webkit-animation: round_info_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: round_info_anim 1.5s ease-in-out infinite alternate;
		animation: round_info_anim 1.5s ease-in-out infinite alternate; 
	}

	@-webkit-keyframes round_info_anim {
		  from {
		    text-shadow: 0 0 10px #fff,
		               0 0 20px  #fff,
		               0 0 30px  #fff,
		               0 0 40px  #FF1177,
		               0 0 70px  #FF1177,
		               0 0 80px  #FF1177,
		               0 0 100px #FF1177;
		  }
		  to {
		    text-shadow: 0 0 5px #fff,
		               0 0 10px #fff,
		               0 0 15px #fff,
		               0 0 20px #FF1177,
		               0 0 35px #FF1177,
		               0 0 40px #FF1177,
		               0 0 50px #FF1177,
		               0 0 75px #FF1177;
		  }
		}


		/*glow for mozilla*/
		@-moz-keyframes round_info_anim {
		  from {
		    text-shadow: 0 0 10px #fff,
		               0 0 20px  #fff,
		               0 0 30px  #fff,
		               0 0 40px  #FF1177,
		               0 0 70px  #FF1177,
		               0 0 80px  #FF1177,
		               0 0 100px #FF1177,
		               0 0 150px #FF1177;
		  }
		  to {
		    text-shadow: 0 0 5px #fff,
		               0 0 10px #fff,
		               0 0 15px #fff,
		               0 0 20px #FF1177,
		               0 0 35px #FF1177,
		               0 0 40px #FF1177,
		               0 0 50px #FF1177,
		               0 0 75px #FF1177;
		  }
		}

		.gray { 
		    -webkit-filter: grayscale(100%);
		    -moz-filter: grayscale(100%);
		    -ms-filter: grayscale(100%);
		    -o-filter: grayscale(100%);		    
		    filter: grayscale(100%);			
		    filter: gray;
		}

  </style>
</head>
<body>
	<div id="sidebar1" class="game13_sidebar" style='display:none'>
		<div id="sidebar1_userhead" class="game13_sidebar"></div>
	</div>
	<div id="sidebar2" class="game13_sidebar"  style='display:none'>
		<div id="sidebar2_userhead" class="game13_sidebar"></div>
	</div>
	<div id="options_area" style="display:none">
	</div>
	<div id="screen_info" class="round_info">
	</div>
	<div id="console" class="console" style="display:none">
		<span id="console_copy" class="console_copy am-icon-copy"> 复制</span>
		<div class="console_container"></div>
	</div>
	<div id="action_pane" style="display:none">
	<div>
</body>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/js/ym_preload.js"></script>
<script src="/js/ym_head_handler.js"></script>
<script src="/js/ZeroClipboard.min.js"></script>
<script type="text/javascript">

var ym_game13_hero = function(data, board){
	var HERO = this;

	HERO.data = {
		blood:1, user:0, ti:12, su:9, gong:7, fang:0, ji:0, superJi:0, index:0, hurt:0, id:0, featureObjs:{}
	};

	HERO.oriIndex = HERO.data.index;

	for(var k in data){
		HERO.data[k] = data[k];
	}

	HERO.parent = board;

	HERO.setBlood = function(blood){
		HERO.data.blood = blood;
		HERO.decorateFeatures();
	};

	HERO.changeIndex = function(index){
		if(HERO.data.index == index){
			return;
		}
		HERO.data.index = index;
		HERO.decorateFeatures();
	};

	HERO.decorateFeatures = function(){

		var bgclr = ['#3a0', '#e26f32', '#96e0fd', '#fae601', '#f97be6']

		var arr2 = ['gong', 'ti', 'ji', 'su', 'fang'];

		var id = (HERO.data.user == 'user1'? 'sidebar1_':'sidebar2_');
		id = id + 'hero' + HERO.data.id;

		if($('#' + id + '_blood').length == 0){
			var html = '';
			// for(var i = 0; i< arr2.length; i++){
			// 	var featureid = id + '_' + arr2[i];
			// 	html += '<div class="game13_hero_feature" id="'+ featureid + '">' +
			// 		'<img src="/game13/pic/' + arr2[i] + '.png" width=' + 16 + ' height=' + 16 + '/>' + 
			// 		'<span id="' + featureid + '_value" class="game13_hero_feature_block_container"></span>' +
			// 		'</div>';
			// }
			html = '<div class="game13_hero_feature_blood" id="' +id + '_blood"><div class="game13_hero_feature_blood_bar" id="' +id+ '_blood_bar"></div></div>';

			$('#' + id).append(html);
	
		}
		// for(var i=0; i<arr2.length; i++){
		// 	var featureid = id + '_' + arr2[i];
		// 	var ob = $('#' + featureid + '_value');
		// 	ob.html(HERO.data[arr2[i]]);
		// 	ob.css('color', bgclr[i]);
		// }	


		var posgrps = HERO.parent[HERO.parent.orientation];
		var heroloc = posgrps[HERO.data.user + '_hero' + HERO.data.index];
		var heropos = {left:heroloc.pos[0], top:heroloc.pos[1], 
					right:heroloc.pos[0] + posgrps.heroCardWidth, bottom:heroloc.pos[1] + posgrps.heroCardHeight};
		var gaplf = 2;

		var linegap = parseInt(posgrps.heroCardHeight / 5);
		var tof = 16;
		if(linegap < 18){
			tof = linegap - gaplf;
		}else{
			linegap = 18;
		}

		var bloodwidth = posgrps.heroCardWidth - HERO.parent.headHeroGap * 2;
		var bloodleft = heropos.left + HERO.parent.headHeroGap;
		var cardbottomheight = parseInt(50 * posgrps.heroCardScale);
		var bloodobj = $('#' + id + '_blood');
		var bloodbarobj = $('#' + id + '_blood_bar');
		var bh = bloodobj.height();
		if(bh > (cardbottomheight - gaplf)){
			bh = cardbottomheight - gaplf;
			bloodobj.css('height', bh + 'px');
			bloodbarobj.css('height', bh + 'px');
		}
		var bloodtop = parseInt(heropos.bottom - ((cardbottomheight - bh) / 2 + bh) );

		bloodobj.css({left:bloodleft + 'px', top:bloodtop + 'px', width:bloodwidth + 'px'}); 
		bloodbarobj.css({width:(HERO.data.blood * 100) +"%"});
		// if(HERO.parent.orientation == 'landscape'){

		// 	var maxw = 0;
		// 	for(var i = 0; i< arr2.length; i++){
		// 		var featureid = id + '_' + arr2[i];
		// 		var featureobj = $('#'+ featureid);		
		// 		var fw = featureobj.width(); 
		// 		if(maxw < fw){
		// 			maxw = fw;
		// 		}						
		// 	}
		// 	for(var i = 0; i< arr2.length; i++){
		// 		var featureid = id + '_' + arr2[i];
		// 		var featureobj = $('#'+ featureid);
		// 		if(tof < 16){
		// 			featureobj.find('img').attr({width:tof, height:tof});
		// 			$('#' + featureid + '_value').css('font-size', tof + 'px');
		// 		}
		// 		var fh = featureobj.height();
		// 		var lft = 0;
		// 		if(HERO.data.user == 'user1'){
		// 			lft = heropos.right + gaplf;
		// 		}else{
		// 			lft = heropos.left - gaplf - maxw;
		// 		}
		// 		var tp = linegap * i + parseInt((linegap - tof)/2) + heropos.top;
		// 		featureobj.css({left:lft + 'px', top:tp + 'px'});
		// 	}

		// }else{
		// 	for(var i = 0; i< arr2.length; i++){
		// 		var featureid = id + '_' + arr2[i];
		// 		var featureobj = $('#'+ featureid);
		// 		if(tof < 16){
		// 			featureobj.find('img').attr({width:tof, height:tof});
		// 			$('#' + featureid + '_value').css('font-size', tof + 'px');
		// 		}
		// 		var fh = featureobj.height();
		// 		var lft = heropos.right + gaplf ;
		// 		var tp = linegap * i + parseInt((linegap - tof)/2) + heropos.top;
		// 		featureobj.css({left:lft + 'px', top:tp + 'px'});
		// 	}
		// }
	};
};

var ym_game13_playboard = function(initContent, gameId, gameUserId){
	var BD = this;

	BD.resourcesName = ['enemy', 'me'];

	BD.initContent = initContent;

	BD.mode = BD.initContent.mode;
	BD.actionPane = $('#action_pane');
	BD.console = $('#console');
	BD.consoleCopy= $('.console_copy');
	BD.loadingCount = 16;
	BD.loadingOriCount = 16;
	BD.preloadProc = 0.4;

	BD.cardNoFontSize = 20;

	BD.healthCheckInterval = 5000;
	
	BD.portraitInited = false;
	BD.landscapeInited = false;
	BD.portraitPreWidth = 40 * 3;//Room for display hero features

	BD.borderBarRate = 1/6;

	BD.headRate = 3/4;
	BD.heroCardOriWidth = 277;
	BD.heroCardOriHeight = 378;

	BD.userHeadOriSize = 174;

	BD.headHeroGap = 10;

	BD.ticket = '';
	BD.stage = 0;
	BD.round = 0;

	BD.preloadObj =  new ym_preload();

	BD.displayConsole = true;

	BD.consoleHeight = 100;

	BD.users = {};
	BD.userHeroOrderIds = {};
	BD.optionsArea = $('#options_area');
	//BD.optionsAreaCanvas = $('#options_area_canvas');
	BD.playAreaBorder = 10;

	BD.screenInfo = $('#screen_info');

	BD.gameId = gameId;
	BD.gameUserId = gameUserId;
	BD.displayMode = 0; //0-animation 1-fast
	BD.displaying = [];
	BD.submiting = false;

	BD.selectedHero = 0;

	BD.healthcheck = function(){
		setTimeout(function(){
			$.getJSON("/game13/wip/healthcheck/" + BD.gameId +"/" + BD.gameUserId, function(result){
					var opp_offline = (result['opp_offline'] == 'true');
					//TODO offline
					BD.healthcheck();
				});
		}, BD.healthCheckInterval);
	};

	BD.makeCenter = function(obj){
		var w = obj.width(), h = obj.height();
		var lft = parseInt((BD.screenW - w)/2);
		var top = parseInt((BD.screenH - h)/2);
		obj.css({left:lft + 'px', top:top +'px'});
	};
	
	BD.showScreenInfo = function(info){
		BD.screenInfo.html(info);
		BD.println(info);
		BD.screenInfo.attr('class', 'round_info');
		BD.screenInfo.css('display', 'block');
		BD.makeCenter(BD.screenInfo);
		BD.screenInfo.animate({'filter':'alpha(opacity=100)', '-moz-opacity':1, 
					'-khtml-opacity':1, 'opacity':1}, 1000, 
			function(){
				setTimeout(function(){
					BD.screenInfo.animate({'filter':'alpha(opacity=0)', '-moz-opacity':0, 
								'-khtml-opacity':0, 'opacity':0}, 1000, function(){
									BD.screenInfo.css('display', 'none');
								});
				
				}, 1000);
			}
		);		
	}
	BD.showRoundInfo = function(round) {
		var rounds = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二', '十三', '十四', '十五'];
		BD.showScreenInfo("第" + rounds[round-1] + "回合");

	};

	BD.evalCardNo = function(v){
		if(v < 11)
			return v;
		var nos = ['J', 'Q', 'K', 'A'];
		return nos[v - 11];
	};

	BD.attachCardNos = function(){
		var cardTypes = ['club', 'heart', 'spade', 'diamond'];
		for(var i=0; i<cardTypes.length; i++){
			for(var j=2; j<=14; j++){
				var key = cardTypes[i] + "_" + j;
				var key_no = key + '_no';
				var curobj = BD.preloaded[key].obj;
				curobj.append('<div id="' + key_no + '" class="card_no">' +BD.evalCardNo(j)+ '</div>');
			}
		}
		for(var j=2; j<=14; j++){
			var key = 'diamond_2_' + j;
			var key_no = key + '_no';
			var curobj = BD.preloaded[key].obj;
			curobj.append('<div id="' + key_no + '" class="card_no">' +BD.evalCardNo(j)+ '</div>');
		}

		BD.cardNoObjs = $('.card_no');
		BD.cardNoObjs.css({'font-size':BD.cardNoFontSize + 'px'});
	};

	BD.packPreloadResources = function(){
		var res = {};
		for(var k in BD.resourcesName){
			var key = BD.resourcesName[k];
			var img = key + '/png';
			if(key.indexOf('.')>=0){
				img = key;
				key = key.substring(0, key.indexOf('.'));
			}
			var one = {name:key, type:'img', path:'/game13/pic/' + img};
			res[key] = one;
		}

		var cardTypes = ['club', 'heart', 'spade', 'diamond'];
		for(var i=0; i<cardTypes.length; i++){
			for(var j=2; j<=14; j++){
				var key = cardTypes[i] + "_" + j;
				var one = {name:key, type:'img', path:'/game13/pic/' + cardTypes[i] + '/jpg'};
				res[key] = one;
			}
		}
		for(var j=2; j<=14; j++){
			var key = 'diamond_2_' + j;
			var img = '/game13/pic/diamond/jpg';
			var one = {name:key, type:'img', path:img};
			res[key] = one;
		}
		res['card_background'] = {name:'card_background', type:'img', path:'/game13/pic/card_back/jpg'};
		BD.preloadObj.setResources(res);
	};

	BD.checkOrientation = function(){
		BD.screenW = $(document.body).width();
		BD.screenH = $(document.body).height();
		if(BD.screenW < BD.screenH){
			BD.orientation = "portrait";
			if(BD.displayConsole){
				BD.screenH = BD.screenH - BD.consoleHeight;
			}
		}else{
			BD.orientation = "landscape";
		}
	};

	BD.preload = function(){
		BD.loadingCount --;
		BD.preloadObj.setPreProgress((BD.loadingOriCount - BD.loadingCount)/BD.loadingOriCount * BD.preloadProc);
		if(BD.loadingCount == 0){
			BD.portraitInited = true;
			BD.landscapeInited = true;

			BD.changeOrientation();
			BD.packPreloadResources();
		}
	};

	BD.startRound = function(data) {
		if(BD.displaying.length > 0){
			for(var k in BD.displaying){
				BD.displaying[k].css('display', 'none');
				BD.displaying[k].attr('active', 'false');
			}
		}
		BD.displaying = [];
		BD.showRoundInfo(data.round);
		BD.gotoShuffleStage();
	};

	BD.commonNextWork = function(){
	    var url = '/game13/wip/next/' + BD.gameId + '/' + BD.gameUserId ;
	    if(BD.ticket != ''){
	      url = url + '/' + BD.ticket;
	    }
	    $.getJSON(url, function(v, status){
	      if(status=='success'){
		      if(v == 'false'||v ===false){
		        BD.commonNextWork();
		      }else{
		        BD.evalCurrentData(v);
		      }	      	
	      }else{
	      	BD.commonNextWork();
	      }

	    });
	};

	BD.commonStageSwitchOperation = function(){
		$.getJSON('/game13/wip/nextstage/' + BD.gameId + '/' + BD.gameUserId, function(v, status){
			if(status=='success'){
				if(v == 'false'||v === false){
					BD.commonNextWork();
				}else{
					BD.evalCurrentData(v);
				}				
			}else{
				setTimeout(function(){
					BD.commonStageSwitchOperation();
				}, 500);
			}

		});
	};

	BD.gotoShuffleStage = function(){
		BD.println("等待派牌...");
		BD.commonStageSwitchOperation();
	};

	BD._eraseDuplicateDuringCompete = function(arr1, arr2){
		var keys = {};
		for(var i=0; i<arr1.length; i++){
			var tk = arr1[i];
			if(typeof(keys[tk])!='undefined'){
				var tks = tk.split('_');
				tk = tks[0] + '_2_' + tks[1];
				arr1[i] = tk; 
			}
			keys[tk] = tk;
		}
		for(var i=0; i<arr2.length; i++){
			var tk = arr2[i];
			if(typeof(keys[tk])!='undefined'){
				var tks = tk.split('_');
				if(tks[0] =='diamond'){
					tk = tks[0] + '_2_' + tks[1];
					arr2[i] = tk; 					
				}

			}
			keys[tk] = tk;
		}
		return [arr1, arr2];
	};

	BD.evalOneRound = function(tk, data, me, enemy){
		var competecards = data.compete[tk];
		var user1level = competecards[enemy.id].level;
		var user2level = competecards[me.id].level;
		var str = '敌方牌型 ' + BD.evalCardLevel(user1level, tk=='middle') + ',';
		str += '我方牌型 ' + BD.evalCardLevel(user2level, tk=='middle') + ',';

		if(competecards[enemy.id].isWin==true){
			str += '敌方赢！';
		}else if(competecards[me.id].isWin==true){
			str += '我方赢！';
		}

		BD.println(str);
	};

	BD.stringRep = function(str, from, to){
		return str.replace(from ,to);
	};

	BD.evalDescription = function(str, ids){
		for(var k in ids){
			var from = '{' + k + '}';
			var to = ids[k];
			str = BD.stringRep(str, from, to);
		}
		return str;
	};

	BD.processCompete = function(data, me, enemy){
		var ids = {};
		var arrs1 = [me, enemy];
		var arrs2 = ['gameHero1', 'gameHero2', 'gameHero3'];
		for(var k in arrs1){
			var usr = arrs1[k];
			for(var i=0; i<arrs2.length; i++){
				ids[usr[arrs2[i]].id]= usr[arrs2[i]].heroname;
			}
		}
		var processData = data.compete.process;
		for(var k in processData){
			var oneprocess = processData[k];
			if(oneprocess.length > 0){
				for(var i =0 ; i< oneprocess.length; i++){
					var curprocess = oneprocess[i];
					if(typeof(curprocess['description'])!='undefined'){
						BD.println(BD.evalDescription(curprocess['description'], ids));
					}
				}
			}
		}
		BD.verifyUserData(me, enemy);
		if(data.wincheck.terminate){
			if(me.id == data.wincheck.win){
				BD.showScreenInfo("恭喜，您赢了！");
			}else if(enemy.id == data.wincheck.win){
				BD.showScreenInfo("很抱歉，您输了");
			}
		}else{
			BD.commonStageSwitchOperation();
		}
	};

	BD.verifyUserData = function(me, enemy){
		var arrs = [me, enemy];
		var keys = ['gameHero1', 'gameHero2', 'gameHero3']; 
		for(var k in arrs){
			var usr = arrs[k];
			for(var i = 0; i< keys.length; i ++){
				var hero = usr[keys[i]];
				var blood = hero.blood/hero.oriblood;
				BD.users[usr.id][hero.id].setBlood(blood);
			}
		}
	};

	BD.illustrateCompete = function(data){
		data = data.data;
		BD.actionPane.css('display', 'none');
		BD.optionsArea.css('display', 'none');
		//re-pos users
		var userdata = data.userdata;
		var tmpuserorderids = {'user1':[], 'user2':[]};
		var me = userdata.game_user2, enemy = userdata.game_user1;
		if(userdata.game_user1.id == BD.gameUserId){
			me = userdata.game_user1;
			enemy = userdata.game_user2;
		}

		var changed = false;
		for(var i=1; i<=3; i++){
			var key = 'gameHero' + i;
			tmpuserorderids.user1[i-1] = enemy[key].id;
			if(BD.userHeroOrderIds.user1[i-1]!=enemy[key].id){
				changed = true;
				BD.users[BD.initContent.enemy.id][enemy[key].id].changeIndex(i);
			}
			tmpuserorderids.user2[i-1] = me[key].id;
			if(BD.userHeroOrderIds.user2[i-1]!=me[key].id){
				changed = true;
				BD.users[BD.initContent.me.id][me[key].id].changeIndex(i);
			}
		}
		BD.userHeroOrderIds = tmpuserorderids;
		if(changed){
			BD.resizeSideBar();
		}

		for(var k in BD.displaying){
			BD.displaying[k].css('display', 'none');
			BD.displaying[k].attr('active', 'false');
		}
		BD.displaying = [];

		if(typeof(data.cards[enemy.id].body)!='undefined'||typeof(data.cards[me.id].body)!='undefined'){
			setTimeout(function(){
				if(typeof(data.cards[enemy.id].body)!='undefined'){
					BD.println("敌方牌型 " + BD.evalCardLevel(data.compete.body[enemy.id].level));
				}
				if(typeof(data.cards[me.id].body)!='undefined'){
					BD.println("我方牌型 " + BD.evalCardLevel(data.compete.body[me.id].level));
				}			
				BD.submiting = false;
				BD.processCompete(data, me, enemy);
			}, 1000);
		}else{
			//start compete cards
			//top
			BD.println("比较顶部");
			var ks = BD._eraseDuplicateDuringCompete(data.cards[enemy.id].top, data.cards[me.id].top);
			// BD.displayCardsInCompare('user1', ks[0]);
			// BD.displayCardsInCompare('user2', ks[1]);
			BD.evalOneRound('top', data, me, enemy);

			setTimeout(function(){
				for(var k in BD.displaying){
					BD.displaying[k].css('display', 'none');
					BD.displaying[k].attr('active', 'false');
				}
				BD.displaying = [];
				//middle
				BD.println("比较中部");
				ks = BD._eraseDuplicateDuringCompete(data.cards[enemy.id].middle, data.cards[me.id].middle);
				// BD.displayCardsInCompare('user1', ks[0]);
				// BD.displayCardsInCompare('user2', ks[1]);
				BD.evalOneRound('middle', data, me, enemy);
				setTimeout(function(){
					for(var k in BD.displaying){
						BD.displaying[k].css('display', 'none');
						BD.displaying[k].attr('active', 'false');
					}
					BD.displaying = [];
					//bottom
					BD.println("比较底部");
					ks = BD._eraseDuplicateDuringCompete(data.cards[enemy.id].bottom, data.cards[me.id].bottom);
					// BD.displayCardsInCompare('user1', ks[0]);
					// BD.displayCardsInCompare('user2', ks[1]);
					BD.evalOneRound('bottom', data, me, enemy);
					BD.submiting = false;

					BD.processCompete(data, me, enemy);
				}, 1000);
			}, 1000);

		}



	};

	BD.evalCardName = function(cardCode){
		var cs = cardCode.split('_');
		var bmap = {'diamond':'方片', 'spade':'黑桃', 'club':'草花', 'heart':'红心'};
		var code = parseInt(cs[cs.length-1]);
		code = BD.evalCardNo(code);

		return bmap[cs[0]] + code;
	};

	BD.evalCardLevel = function(level, ismiddle){
		if(typeof(ismiddle)=='undefined'){
			ismiddle = false;
		}
		var levels = ['乌龙', '一对', '两对', '三条', '顺子', '同花', '葫芦', '铁支', '同花顺', '五同', '一条龙', '大青龙'];
		level = parseInt(level);
		if(level == 7 && ismiddle){
			return '中墩葫芦';
		}else{
			return levels[level - 1];
		}

	};

	BD.println = function(info){
		var obj = $('.console_container');
		obj.append(info + "<br/>");
		obj.scrollTop(obj[0].scrollHeight);
		BD.console.scrollTop(BD.console[0].scrollHeight);
	};

	
	BD.cleanWhenStageChanged = function(){
		BD.cleanSelectedHero();
	};

	BD.evalCurrentData = function(data) {
		BD.ticket = data.ticket;
		BD.stage = parseInt(data.stage);
		BD.cleanWhenStageChanged();
		BD.round = parseInt(data.round);
		BD.setConsoleSize();
		switch(BD.stage){
			case 0:
				BD.startRound(data); break;
			case 1:
				BD.cardOptionsInited = false;
				BD.loadDispatchedCards(data); break;
			case 2:
				BD.illustrateCompete(data);break;
		}
	};

	BD.changeOrientation = function(){
		BD.checkOrientation();

		var unorien = (BD.orientation=='portrait'?'landscape':'portrait');
		$('._' + unorien).css('display', 'none');
		BD.resize();
		$('._' + BD.orientation).css('display', 'block');

		//-------Test-----------
		//BD._xxx_testDisplay();
	};


	BD.recordCurrentTime = function(){
		var ltim = new Date().getTime();
		BD.timeChecker.attr('curtime', ltim);
		return ltim;
	};

	BD.getRecordedTimeDiff = function(){
		if(typeof(BD.timeChecker.attr('curtime'))=='undefined'){
			return 0;
		}else{
			var pastTime = parseInt(BD.timeChecker.attr('curtime'));
			var ltim = new Date().getTime();
			return ltim - pastTime;
		}
	};


	BD.initUsers = function(){
		BD.users = {
		};
		BD.userHeroOrderIds = {'user1':[], 'user2':[]};
		BD.users[BD.initContent.me.id] = {};
		BD.users[BD.initContent.enemy.id] = {};
		var keyidx = [1, 2, 3];
		for(var i=0; i<keyidx.length; i++){
			var key = 'game_hero' + keyidx[i];
			var data = BD.initContent.enemy[key];
			data['index'] = keyidx[i];
			data['user'] = 'user1';
			BD.users[BD.initContent.enemy.id][BD.initContent.enemy[key].id] = new ym_game13_hero(data, BD);
			BD.userHeroOrderIds.user1[i] = BD.initContent.enemy[key].id;

			data = BD.initContent.me[key];
			data['index'] = keyidx[i];
			data['user'] = 'user2';
			BD.users[BD.initContent.me.id][BD.initContent.me[key].id] = new ym_game13_hero(data, BD);
			BD.userHeroOrderIds.user2[i] = BD.initContent.me[key].id;
		}
	};

	BD.initSidebarObjs = function(){
		var sidebar1 = $('#sidebar1');
		var sidebar2 = $('#sidebar2');
		var henemy = ''; var hme = '';
		for(var i=1; i<=3; i++){
			var key = 'game_hero' + i;
			henemy += '<div id="sidebar1_hero' +  BD.initContent.enemy[key].id + '" class="game13_sidebar"></div>';
			hme += '<div id="sidebar2_hero' +  BD.initContent.me[key].id + '" class="game13_sidebar"></div>';
		}

		sidebar1.append(henemy);
		sidebar2.append(hme);
	};
		// <div id="sidebar1_hero1" class="game13_sidebar"></div>
		// <div id="sidebar1_hero2" class="game13_sidebar"></div>
		// <div id="sidebar1_hero3" class="game13_sidebar"></div>
	BD.init = function(){
		var clip = new ZeroClipboard($('.console_copy')[0]);
		clip.on("copy", function(e){
			var text = $('.console_container').html().replace(new RegExp('<br>',"gm"),'\r\n');
		    e.clipboardData.setData("text/plain", text);
		});

		$(document.body).append('<span id="time_checker" style="display:none" />');

		BD.timeChecker = $('#time_checker');
		BD.recordCurrentTime();

		BD.portrait = {user1:{}, user1_hero1:{}, user1_hero2:{},user1_hero3:{},user2:{}, user2_hero1:{},user2_hero2:{},user2_hero3:{}};
		BD.landscape = {user1:{}, user1_hero1:{}, user1_hero2:{},user1_hero3:{},user2:{}, user2_hero1:{},user2_hero2:{},user2_hero3:{}};
		BD.checkOrientation();

		BD.initUsers();

		BD.initSidebarObjs();

		BD.preloadObj.startLoad({res:{}, img:'/game13/pic/preloadPic'}, function(){
			BD.afterLoadEverything();
		});
		
		for(var k in BD.userHeroOrderIds){
			var initkey = (k=='user1'?'sidebar1_':'sidebar2_');
			var arr = ['userhead'];
			var tar = BD.userHeroOrderIds[k];
			for(var m=0; m < tar.length; m++ ){
				arr[arr.length] = 'hero' + tar[m];
			}
			for(var l in arr){
				var kid1 = initkey + arr[l];
				$('#' + kid1).append('<div id="' + kid1 +'_portrait" class="_portrait" style="display:none"></div><div id="' +
							 kid1 +'_landscape" class="_landscape"  style="display:none"></div>');				
			}
		}

		BD.initPortraitUI();
		BD.initLandscapeUI();

		window.addEventListener("orientationchange", function() {
			BD.changeOrientation();

		}, false);

		BD.attachHeroSwitchEvent();
	};

	BD.attachHeroSwitchEvent = function(){
		for(var i=0; i<3; i++){
			var obj = $('#sidebar2_hero' + BD.userHeroOrderIds.user2[i]);
			obj.on('click', function(){
				var o = $(this);
				BD.heroSwitchEvent(o);
			});
		}
	};

	BD.cleanSelectedHero = function(){
		if(BD.selectedHero!=0){
			BD.selectedHero.find('img').css('box-shadow', 'none');
		}
		BD.selectedHero = 0;

	};

	BD.heroSwitchEvent = function(o) {
		if(BD.stage !=1)
			return;
		if(BD.selectedHero == 0){
			o.find('img').css('box-shadow', '0px 0px 5px 5px #cfa');
			BD.selectedHero = o;
		}else{
			BD.heroSwitch(BD.selectedHero, o);
		}
		
	};

	BD.heroSwitch = function(o1, o2){
		var i1 = parseInt(o1.attr('idx'));
		var i2 = parseInt(o2.attr('idx'));
		var hroid1 = BD.userHeroOrderIds.user2[i1 - 1];
		var hroid2 = BD.userHeroOrderIds.user2[i2 - 1];
		BD.userHeroOrderIds.user2[i1 - 1] = hroid2;
		BD.userHeroOrderIds.user2[i2 - 1] = hroid1;

		BD.cleanSelectedHero();
		BD.resizeSideBar();
		BD.users[BD.initContent.me.id][hroid1].changeIndex(i2);
		BD.users[BD.initContent.me.id][hroid2].changeIndex(i1);
	};

	BD.decorateHeroCard = function(parent, pos){
		parent.find('game13_hero_card_front').css({'position':'fixed', 'z-index':100, 'left':pos[0] + 'px', 'top':pos[1] + 'px'});
		BD.preload();
	};

	BD.afterLoadEverything = function(){


		$('#sidebar1').css('display', 'block');
		$('#sidebar2').css('display', 'block');

		$('#enemy').attr({'class':'absolute_pos', 'width':'30', 'height':'30'});
		$('#enemy').css({width:'30px', height:'30px'});
		$('#me').attr({'class':'absolute_pos', 'width':'30', 'height':'30'});
		$('#me').css({width:'30px', height:'30px'});
		BD.preloaded = BD.preloadObj.getResources();
		BD.resize();

		if(BD.displayConsole){
			BD.console.css('display', 'block');
		}

		BD.attachCardNos();

		//-------Test-----------
		//BD._xxx_testDisplay();

		
		BD.healthcheck();

		BD.evalCurrentData(BD.initContent);
	};

	BD.eraseDuplicate = function(cardOptions){
		for(var k in cardOptions){
			var one = cardOptions[k].cards;
			var arr = {};
			for(var m in one){
				var arrsub = one[m];
				for(var s in arrsub){
					var cur = arrsub[s];
					if(typeof(arr[cur])!='undefined'){
						var tmp = cur.split('_');
						cur = tmp[0] + '_2_' + tmp[1];
						arrsub[s] = cur;
					}
					arr[cur] = cur;					
				}
			}
		}
	};

	BD._xxx_testDisplay = function(){
		if(typeof(BD.preloaded)!='undefined'){
	
			if(typeof(BD.cardOptions)=='undefined'){
				BD.loadDispatchedCards();
			}else{
				BD.displayCardsInPrepare(BD.cardOptions);	
			}
			
			BD.showRoundInfo(1);
		}
	};

	BD.loadDispatchedCards = function(data){
		BD.submitted = false;
		BD.eraseDuplicate(data.data);
		BD.startTimeChecker();
		BD.displayCardsInPrepare(data.data);
		BD.println("准备选牌");
		BD.startTimer();
	};

	BD.startTimeChecker = function(){
		BD.recordCurrentTime();
		var func = function(){
			if(BD.getRecordedTimeDiff()/1000 > 30){
				if(!BD.submitted){
					//TODO ... check
				}
			}else{
				setTimeout(func, 1000);
			}
		};

		func();
	};

	BD.resize = function(){
		var markdis = 30;
		$('#enemy').css({left:(BD[BD.orientation].userHeadSize - markdis) + 'px', top:(BD[BD.orientation].userHeadSize - markdis) + 'px', display:'block'});
		$('#me').css({left:(BD.screenW - markdis) + 'px', top:(BD.screenH - markdis) + 'px', display:'block'});
		BD.resizeSideBar();
	};

	BD.reposSideBar = function(orientation){
		if(typeof(orientation)=='undefined'){
			orientation = BD.orientation;
		}
		if(orientation == 'portrait'){
			var featureroom = BD.portraitPreWidth / 3;
			BD.portrait.user1.pos = [0, 0];
			BD.portrait.user1_hero1.pos = [BD.portrait.userHeadSize + BD.headHeroGap, 0];
			BD.portrait.user1_hero2.pos = [BD.portrait.userHeadSize + BD.headHeroGap+ BD.portrait.heroCardWidth + featureroom , 0];
			BD.portrait.user1_hero3.pos = [BD.portrait.userHeadSize + BD.headHeroGap+ BD.portrait.heroCardWidth * 2 + featureroom * 2, 0];
			BD.portrait.user2.pos = [BD.screenW - BD.portrait.userHeadSize, BD.screenH - BD.portrait.userHeadSize];
			BD.portrait.user2_hero1.pos = [(BD.screenW - (BD.portrait.userHeadSize + BD.headHeroGap) - BD.portrait.heroCardWidth * 3 -featureroom * 3), 
									BD.screenH - BD.portrait.heroCardHeight];
			BD.portrait.user2_hero2.pos = [(BD.screenW - (BD.portrait.userHeadSize + BD.headHeroGap) - BD.portrait.heroCardWidth * 2 - featureroom * 2), 
									BD.screenH - BD.portrait.heroCardHeight];
			BD.portrait.user2_hero3.pos = [(BD.screenW - (BD.portrait.userHeadSize + BD.headHeroGap) - BD.portrait.heroCardWidth - featureroom ), 
									BD.screenH - BD.portrait.heroCardHeight];

			var top =  BD.portrait.heroCardHeight;
			BD.portrait.cardDisplayArea = {
				left:0, top: top, right:BD.screenW, bottom: (BD.screenH - top), width:BD.screenW, height:(BD.screenH - top * 2)
			};

		}else{
			BD.landscape.user1.pos = [0, 0];
			BD.landscape.user1_hero1.pos = [0, (BD.landscape.userHeadSize + BD.headHeroGap)];
			BD.landscape.user1_hero2.pos = [0, (BD.landscape.userHeadSize + BD.headHeroGap+ BD.landscape.heroCardHeight)];
			BD.landscape.user1_hero3.pos = [0, (BD.landscape.userHeadSize + BD.headHeroGap+ BD.landscape.heroCardHeight * 2)];
			BD.landscape.user2.pos = [BD.screenW - BD.landscape.userHeadSize, BD.screenH - BD.landscape.userHeadSize];
			BD.landscape.user2_hero1.pos = [BD.screenW - BD.landscape.heroCardWidth, 
									(BD.screenH - (BD.landscape.userHeadSize + BD.headHeroGap) - BD.landscape.heroCardHeight * 3)];
			BD.landscape.user2_hero2.pos = [BD.screenW - BD.landscape.heroCardWidth, 
									(BD.screenH - (BD.landscape.userHeadSize + BD.headHeroGap) - BD.landscape.heroCardHeight * 2)];
			BD.landscape.user2_hero3.pos = [BD.screenW - BD.landscape.heroCardWidth, 
									(BD.screenH - (BD.landscape.userHeadSize + BD.headHeroGap) - BD.landscape.heroCardHeight )];

			var left = BD.landscape.heroCardWidth + 48;
			BD.landscape.cardDisplayArea = {
				left: left, top:0, right: (BD.screenW - left), bottom:BD.screenH, width: (BD.screenW - left * 2), height: BD.screenH
			};

			if(BD.displayConsole){
				BD.landscape.cardDisplayArea.height = BD.landscape.cardDisplayArea.height - BD.consoleHeight;
				BD.landscape.cardDisplayArea.bottom = BD.landscape.cardDisplayArea.bottom - BD.consoleHeight;
			}
			
		}
		var ar = BD[orientation].cardDisplayArea;
		BD[orientation].cardDisplayArea = {
			left: ar.left + BD.playAreaBorder, top:ar.top + BD.playAreaBorder, right: ar.right - BD.playAreaBorder, bottom:ar.bottom - BD.playAreaBorder, 
					width: ar.width - BD.playAreaBorder * 2, height: ar.height - BD.playAreaBorder * 2
		};		

		if(BD.displayConsole){
			BD.setConsoleSize(orientation);

		}

	};

	BD.setConsoleSize = function(orientation){
		if(typeof(orientation)=='undefined'){
			orientation = BD.orientation;
		}
		var top = (BD.stage==2?BD[orientation].cardDisplayArea.top:BD[orientation].cardDisplayArea.bottom);//BD[orientation].cardDisplayArea.bottom;
		var width = BD[orientation].cardDisplayArea.width;
		var left = BD[orientation].cardDisplayArea.left;
		var height = (BD.stage==2?(BD[orientation].cardDisplayArea.height +BD.consoleHeight):BD.consoleHeight);//BD.consoleHeight;
		if(orientation=='portrait'){
			left -= BD.playAreaBorder;
			top += BD.portrait.heroCardHeight + BD.playAreaBorder ;
			width += BD.playAreaBorder ;
			height -= BD.playAreaBorder;
		}
		BD.console.css({'width':width + 'px', 'height':height + 'px', 'top': top+ 'px', 'left': left + 'px'});
		BD.consoleCopy.css({'top':(top + 10) + 'px', 'left':(left + width - 80) + 'px'});
	};

	BD.cloneCardOptions = function(cardOptions){
		var cloned = [];
		for(var k in cardOptions){
			cloned[k] = {name:[], cards:{top:[], middle:[], bottom:[], extra:[]}};
			for(var m in cardOptions[k].name){
				cloned[k].name.push(cardOptions[k].name[m]);
			}
			for(var s in cardOptions[k].cards){
				var cur = cardOptions[k].cards[s];
				for(var n in cur){
					cloned[k].cards[s].push(cur[n]);
				}
			}			
		}
		return cloned;
	};

	BD.refreshCardOptions = function(){
		BD.cardOptions = BD.cloneCardOptions(BD.oriCardOptions);
		BD.displayCardsInPrepare(BD.cardOptions, BD.cardOptionsSelected);
	};

	BD.initCardOptions = function(cardOptions, h, w, fontsize){
		var html = '';
		var idx = 0;

		BD.oriCardOptions = BD.cloneCardOptions(cardOptions);
		for(var k in cardOptions){
			var clz = (idx == 0?'option_block option_selected':'option_block');
			if(idx == 0){
				BD.optionsSelected = k;
			}
			html += '<div class="'+clz+'" idx="' + k + '" id="card_option_'+ k +'">';
			for(var m in cardOptions[k].name){
				html += '<div class="option_block_inside">' + cardOptions[k].name[m] + '</div>';
			}
			html += '</div>';
			idx ++;
		}


		BD.optionsArea.html(html);
		var optionobjs = $('.option_block');
		optionobjs.css({'height':h + 'px', 'width':w + 'px'});
		$('.option_block_inside').css('font-size', fontsize + 'px');

		optionobjs.on('click', function(){
			var idx = parseInt($(this).attr('idx'));
			if(idx != BD.optionsSelected){
				 $('.option_block').attr('class', 'option_block');
				 $('#card_option_' + idx).attr('class', 'option_block option_selected');
				 BD.optionsSelected = idx;
				 BD.displayCardsInPrepare(BD.cardOptions, idx);
			}
		});



		BD.cardOptionsInited = true;

	};

	BD.cardPrepareClickAction = function(cardobj){
		if(cardobj.attr('active')=='false'){
			return;
		}
		var preselectedobj = false;
		if(typeof(BD.cardSelected)!='undefined'&&BD.cardSelected!==false){
			if(BD.cardSelected!=cardobj.attr('key')){
				preselectedobj = BD.preloaded[BD.cardSelected].obj;
				preselectedobj.css('box-shadow', 'none');
			}else{
				return;
			}

		}

		BD.cardSelected = cardobj.attr('key');
		cardobj.css('box-shadow', '0px 0px 5px 5px #cfa');

		if(preselectedobj!==false&&preselectedobj.attr('active')=='true'){
			if(preselectedobj.attr('is_extra')!=cardobj.attr('is_extra')){
				BD.cardSwitch(preselectedobj, cardobj);
				BD.cardOptions[BD.cardOptionsSelected]['changed'] = 'true';
			}
		}

	};

	BD.cardSwitch = function(card1, card2){
		var key1 = card1.attr('key');
		var key2 = card2.attr('key');
		var cards = BD.cardOptions[BD.cardOptionsSelected].cards;

		var css1 = {'width':card1.css('width'), 'height':card1.css('height'), 'left':card1.css('left'), 'top':card1.css('top')};
		var css2 = {'width':card2.css('width'), 'height':card2.css('height'), 'left':card2.css('left'), 'top':card2.css('top')};

		var extra1 = card1.attr('is_extra');
		var extra2 = card2.attr('is_extra');

		card1.animate(css2, 300, function(){
			BD._repos_cardno($(this));
		});
		card2.animate(css1, 300, function(){
			BD._repos_cardno($(this));
		});

		card1.attr('is_extra', extra2);
		card2.attr('is_extra', extra1);

		var pos1 = BD.displayingCardKeys[key1];
		var pos2 = BD.displayingCardKeys[key2];

		cards[pos1.l][pos1.d] = key2;
		cards[pos2.l][pos2.d] = key1;

		BD.displayingCardKeys[key1] = pos2;
		BD.displayingCardKeys[key2] = pos1;		
	};

	BD._repos_cardno = function(cardobj){
		var no = cardobj.find('.card_no');
		no.css({'margin-left':'0px', 'margin-top':'-' + cardobj.css('height'), 
					'line-height':cardobj.css('height'), width:cardobj.css('width'), height:cardobj.css('height')});
	};

	BD.submitCards = function(){
		if(BD.submiting){
			return;
		}
		BD.submiting = true;
		if(typeof(BD.cardOptionsSelected)=='undefined'){
			BD.cardOptionsSelected = 0;
		}
		var submitData = BD.cardOptions[BD.cardOptionsSelected];
		var hros = BD.userHeroOrderIds.user2[0] + ',' + BD.userHeroOrderIds.user2[1] + ',' + BD.userHeroOrderIds.user2[2];
		submitData['heros'] = hros;
		$.post('/game13/wip/submitcards', {'_token':$('meta[name="csrf-token"]').attr('content'), gameUserId:BD.gameUserId, gameId:BD.gameId,
          'data':JSON.stringify(submitData)}, 
		function(v, status){
			if(status == 'success'){
				if(v == 'false'||v===false){
					BD.commonStageSwitchOperation();
				}else{
					BD.evalCurrentData(JSON.parse(v));
				}				
			}else{
				setTimeout(function(){
					BD.submitCards();
				}, 500);
			}

		});
	};

	BD.startTimer = function(){
		BD.timer = (BD.mode=='test'?5:30);
		$('#prepare_timer').html(BD.timer);
		var func = function(){
			BD.timer --;
			$('#prepare_timer').html(BD.timer);
			if(BD.timer > 0){
				setTimeout(function(){func();}, 1000);
			}else{
				BD.submitCards();
			}
		};

		setTimeout(function(){func();}, 1000);
	};

	BD.initPrepareCardActions = function(){
		var html = '<button type="button" class="am-btn am-btn-primary am-round" id="prepare_timer">'+BD.timer+'</button>'+
					'<button type="button" class="am-btn am-btn-secondary am-round" id="prepare_refresh"><i class="am-icon-refresh"></i></button>' +
						'<button type="button" class="am-btn am-btn-success am-round" id="prepare_submit"><i class="am-icon-arrow-right"></i></button>'
		BD.actionPane.html(html);

		$('#prepare_refresh').on('click', function(){
			BD.refreshCardOptions();
		});

		$('#prepare_submit').on('click', function(){
			BD.submitCards();
		});

	};
	//[{name:['a', 'b', 'c'], cards:{top, middle, bottom, extra}}, {...}]
	BD.displayCardsInPrepare = function(cardOptions, optIdx) {
		if(typeof(optIdx) == 'undefined'){
			optIdx = 0;
		}
		BD.displaying= [];

		if($('#prepare_refresh').length==0){
			BD.initPrepareCardActions();
		}

		var displayArea = BD[BD.orientation].cardDisplayArea;
		var unitHeight = Math.floor(displayArea.height / 4);

		var fontOri = 20;
		BD.cardOptionsWidth = 100;
		BD.cardOptionsHeight = 120;

		if(BD.cardOptionsHeight > unitHeight){
			var rate = unitHeight / BD.cardOptionsHeight;
			BD.cardOptionsHeight = unitHeight;
			BD.cardOptionsWidth = Math.floor(BD.cardOptionsWidth * rate);
			fontOri = Math.floor(fontOri * rate);
		}


		var gap = BD.headHeroGap;
		BD.cardOptions = cardOptions;
		
		if(typeof(BD.cardSelected)!='undefined'&&BD.cardSelected!==false){
			BD.preloaded[BD.cardSelected].obj
					.css('box-shadow', 'none');
		}
		BD.cardSelected = false;

		BD.extraCardSizeRatio = 0.8;

		var optionsSize =0;
		var curCards = false;
		for(var k in cardOptions){
			if(optionsSize == optIdx){
				curCards = cardOptions[k].cards;
				BD.cardOptionsSelected = k;
			}
			optionsSize ++;

		}

		if(typeof(BD[BD.orientation].cardPrepareWidth)=='undefined'){
			var width = parseInt((displayArea.width - 8 * gap)/(5 + BD.extraCardSizeRatio)); 
			var sampleobj = BD.preloaded['club_2'].obj;
			var w = sampleobj.width(); var h = sampleobj.height();
			var rate = width / w;
			var height = parseInt(h * rate);
			var ttheight = height * 3 + gap * 5 + BD.cardOptionsHeight;
			if(ttheight > displayArea.height){
				height = parseInt((displayArea.height - gap * 5 - BD.cardOptionsHeight) / 3);
				rate = height / h;
				width = parseInt(w * rate);
			}
			BD[BD.orientation].cardPrepareWidth = width;
			BD[BD.orientation].cardPrepareHeight = height;
		}

		var canvasWidth = (BD.cardOptionsWidth + gap) * optionsSize;
		var thecss = {width:(displayArea.width - gap * 2) + 'px', height:BD.cardOptionsHeight + 'px',
				 left:(displayArea.left + gap) + 'px', top:(displayArea.bottom - BD.cardOptionsHeight - gap) + 'px', display:'block'};
		BD.optionsArea.css(thecss);

		if(typeof(BD.cardOptionsInited)=='undefined'||!BD.cardOptionsInited){
			BD.initCardOptions(BD.cardOptions, BD.cardOptionsHeight, BD.cardOptionsWidth, fontOri);
		}else{
			$('.option_block').css({'height':BD.cardOptionsHeight + 'px', 'width':BD.cardOptionsWidth + 'px'});
			$('.option_block_inside').css('font-size', fontOri + 'px');
		}

		var ct = 0, tlft = 0, ttop = 0;
		var cwidth = BD[BD.orientation].cardPrepareWidth;
		var cheight = BD[BD.orientation].cardPrepareHeight;

		if(typeof(BD.cardNoObjs)!='undefined'){
			BD.cardNoObjs.css({'margin-left':'0px', 'margin-top':(cheight * -1) + 'px', width:cwidth + 'px', height:cheight + 'px', 
					'line-height':cheight + 'px'});
		}

		//Draw top
		ct = 0;
		tlft = displayArea.left + gap; ttop = displayArea.top + gap;

		var ori = {};
		if(typeof(BD.displayingCardKeys)!='undefined'){
			ori = BD.displayingCardKeys;
		}
		BD.displayingCardKeys = {};
		for(var k in curCards){
			for(var m in curCards[k]){
				var key = curCards[k][m];
				BD.displayingCardKeys[key] = {l:k, d:m};
				var curobj = BD.preloaded[key].obj;
				curobj.css({'border':'solid 2px #aaa', 'border-radius':'5px', '-moz-border-radius':'5px', '-webkit-border-radius':'5px'});
				curobj.attr({'active':'true'});
				if(typeof(curobj.attr('action_attached'))=='undefined'){
					curobj.attr({'action_attached':'true', 'key':key});
					curobj.on('click', function(){
						BD.cardPrepareClickAction($(this));
					});
				}
			}
		}

		for(var k in ori){
			var curobj = BD.preloaded[k].obj;
			if(typeof(BD.displayingCardKeys[k])=='undefined'){
				curobj.css('display', 'none');
				curobj.attr('active', 'false');
			}
		}

		var anim_time = 300;
		var l = 0;
		for(var k in curCards.top){
			var key = curCards.top[k];
			var curobj = BD.preloaded[key].obj;
			curobj.css({width:cwidth+ 'px', height:cheight + 'px', display:'block' });
			BD.displaying[BD.displaying.length] = curobj;
			l = (tlft + (cwidth + gap) * ct);
			var pos = {left:l + 'px',
						top:ttop + 'px'};
			if(anim_time == 0){
				curobj.css(pos);
			}else{
				curobj.animate(pos, anim_time);
			}
			
			curobj.attr({width:cwidth, height:cheight, 'is_extra':'false'});
			ct++;
		}

		//Draw middle
		ct = 0;
		ttop = ttop + BD[BD.orientation].cardPrepareHeight + gap;
		for(var k in curCards.middle){
			var key = curCards.middle[k];
			var curobj = BD.preloaded[key].obj;
			curobj.css({width:cwidth+ 'px', height:cheight + 'px', display:'block' });
			BD.displaying[BD.displaying.length] = curobj;
			var pos = {left:(tlft + (cwidth + gap) * ct) + 'px',
						top:ttop + 'px'};
			if(anim_time == 0){
				curobj.css(pos);
			}else{
				curobj.animate(pos, anim_time);
			}

			curobj.attr({width:cwidth, height:cheight, 'is_extra':'false'});
			ct++;
		}
		//Draw bottom
		ct = 0;
		ttop = ttop + BD[BD.orientation].cardPrepareHeight + gap;
		for(var k in curCards.bottom){
			var key = curCards.bottom[k];
			var curobj = BD.preloaded[key].obj;
			curobj.css({width:cwidth+ 'px', height:cheight + 'px',
						 display:'block' });

			BD.displaying[BD.displaying.length] = curobj;
			curobj.attr({width:cwidth, height:cheight, 'is_extra':'false'});

			var pos = {left:(tlft + (cwidth + gap) * ct) + 'px',
						top:ttop + 'px'};
			if(anim_time == 0){
				curobj.css(pos);
			}else{
				curobj.animate(pos, anim_time);
			}

			ct++;
		}
		//Draw extra
		ct = 0;
		var twidth = parseInt(cwidth * BD.extraCardSizeRatio);
		var theight = parseInt(cheight * BD.extraCardSizeRatio);
		tlft = displayArea.right - gap - twidth;
		ttop = ttop + BD[BD.orientation].cardPrepareHeight - theight * 3 - gap * 2;
		for(var k in curCards.extra){
			var key = curCards.extra[k];
			var curobj = BD.preloaded[key].obj;
			curobj.css({width:twidth+ 'px', height:theight + 'px',
						 display:'block' });
			BD.displaying[BD.displaying.length] = curobj;
			$('#' + key + '_no').css({'margin-left':'0px', 'margin-top':(theight * -1) + 'px', width:twidth + 'px', height:theight + 'px', 
					'line-height':theight + 'px'});
			var pos = {left:tlft + 'px',
						top:(ttop + (theight + gap) * ct) + 'px'};
			if(anim_time == 0){
				curobj.css(pos);
			}else{
				curobj.animate(pos, anim_time);
			}

			curobj.attr({width:twidth, height:theight, 'is_extra':'true'});
			ct++;
		}

		BD.actionPane.css({left:(tlft - BD.actionPane.width())+"px", top: (displayArea.top + gap) + "px", display:'block'});
	};

	BD.displayCardsInCompare = function(user, cards) {
		var cardobjs = {};
		var w = 0, h = 0, cardsize = 0;
		var str = (user=='user1'?'对方':'我方') + ':';
		for(var k in cards){
			cardsize ++;
			var key = cards[k];
			str += BD.evalCardName(key) + ' ';
			if(typeof(BD.preloaded[key]) == 'undefined'){
				alert(key);
			}
			cardobjs[key] = BD.preloaded[key].obj;
			cardobjs[key].attr('class', 'absolute_pos');
			w = BD.preloaded[key].obj.width();
			h = BD.preloaded[key].obj.height();
		}
		BD.println(str);
		if(typeof(BD.cardobjs) == 'undefined'){
			BD.cardobjs = {};
		}
		BD.cardobjs[user] = cardobjs;

		var gap = BD.headHeroGap;
		if(BD.orientation == 'portrait'){
			if(typeof(BD[BD.orientation].cardCompareWidth) == 'undefined'){
				var width = parseInt((BD.portrait.cardDisplayArea.width - 4 * gap) / 3);
				var rate = width / w;
				var height = parseInt(h * rate);
				var ttheight = height * 4 + gap * 6;
				if(ttheight > BD.portrait.cardDisplayArea.height){
					height = parseInt((BD.portrait.cardDisplayArea.height - gap * 6) / 4);
					rate = height / h;
					width = parseInt(rate * w);
				}
				BD.portrait.cardCompareWidth = width;
				BD.portrait.cardCompareHeight = height;
			}
			var hgap = 0, wgap = 0, wgap2 =0, ttop = 0, ml = 0, mt = 0;
			wgap = parseInt((BD.portrait.cardDisplayArea.width - BD.portrait.cardCompareWidth * 3- gap * 2)/2);

			if(cardsize == 3){
				hgap = parseInt((BD.portrait.cardDisplayArea.height / 2 - BD.portrait.cardCompareHeight) /2);
				ttop = BD.portrait.cardDisplayArea.top + ((user == 'user1')?hgap:(parseInt(BD.portrait.cardDisplayArea.height / 2) + hgap));
				var ct = 0;
				for(var k in cardobjs){
					var cobj = cardobjs[k];
					ml = wgap + (BD.portrait.cardCompareWidth + gap) * ct;
					mt = ttop;
					cobj.css({left:ml + 'px', top:mt + 'px', width:BD.portrait.cardCompareWidth + 'px', height:BD.portrait.cardCompareHeight + 'px', display:'block'});
					cobj.css({'border':'solid 2px #aaa', 'border-radius':'5px', '-moz-border-radius':'5px', '-webkit-border-radius':'5px'});
					BD.displaying[BD.displaying.length] = cobj;
					cobj.attr({width:BD.portrait.cardCompareWidth, height:BD.portrait.cardCompareHeight});
					ct ++;
				}
			}else if(cardsize == 5){
				wgap2 = parseInt((BD.portrait.cardDisplayArea.width - BD.portrait.cardCompareWidth * 2 - gap)/2);
				hgap = parseInt((BD.portrait.cardDisplayArea.height / 2 - BD.portrait.cardCompareHeight * 2 - gap) /2);
				ttop = BD.portrait.cardDisplayArea.top + ((user == 'user1')?hgap:(parseInt(BD.portrait.cardDisplayArea.height / 2) + hgap));
				var ct = 0;
				for(var k in cardobjs){
					var cobj = cardobjs[k];
					if(user == 'user1'){
						if(ct < 3){
							ml = wgap + (BD.portrait.cardCompareWidth + gap) * ct;
							mt = ttop;
						}else{
							ml = wgap2 + (BD.portrait.cardCompareWidth + gap) * (ct - 3);
							mt = ttop + BD.portrait.cardCompareHeight + gap;
						}
					}else{
						if(ct < 2){
							ml = wgap2 + (BD.portrait.cardCompareWidth + gap) * ct;
							mt = ttop;
						}else{
							ml = wgap +  (BD.portrait.cardCompareWidth + gap) * (ct - 2);
							mt = ttop + BD.portrait.cardCompareHeight + gap;
						}
					}
					cobj.css({left:ml + 'px', top:mt + 'px', width:BD.portrait.cardCompareWidth + 'px', height:BD.portrait.cardCompareHeight + 'px', display:'block'});
					cobj.css({'border':'solid 2px #aaa', 'border-radius':'5px', '-moz-border-radius':'5px', '-webkit-border-radius':'5px'});
					BD.displaying[BD.displaying.length] = cobj;
					cobj.attr({width:BD.portrait.cardCompareWidth, height:BD.portrait.cardCompareHeight});
				
					ct ++;
				}
			}
		}else{
			if(typeof(BD[BD.orientation].cardCompareWidth) == 'undefined'){
				var width = parseInt((BD.landscape.cardDisplayArea.width - 6 * gap) / 4);
				var rate = width / w;
				var height = parseInt(h * rate);
				var ttheight = height * 3 + gap * 4;
				if(ttheight > BD.landscape.cardDisplayArea.height){
					height = parseInt((BD.landscape.cardDisplayArea.height - gap * 4) / 3);
					rate = height / h;
					width = parseInt(rate * w);
				}
				BD.landscape.cardCompareWidth = width;
				BD.landscape.cardCompareHeight = height;
			}

			var hgap = 0, hgap2 = 0, wgap = 0, tleft = 0, ml = 0, mt = 0;

			hgap = parseInt((BD.landscape.cardDisplayArea.height - BD.landscape.cardCompareHeight * 3 - gap * 2) / 2);

			if(cardsize == 3){
				wgap = parseInt((BD.landscape.cardDisplayArea.width/2 - BD.landscape.cardCompareWidth)/2);
				tleft = BD.landscape.cardDisplayArea.left + ((user=='user1')?wgap:parseInt(BD.landscape.cardDisplayArea.width/2 + wgap));
				var ct = 0;
				for(var k in cardobjs){
					var cobj = cardobjs[k];
					ml = tleft;
					mt = hgap + (BD.landscape.cardCompareHeight + gap) * ct;
					cobj.css({left:ml + 'px', top:mt + 'px', width:BD.landscape.cardCompareWidth + 'px', height:BD.landscape.cardCompareHeight + 'px', display:'block'});
					cobj.css({'border':'solid 2px #aaa', 'border-radius':'5px', '-moz-border-radius':'5px', '-webkit-border-radius':'5px'});
					BD.displaying[BD.displaying.length] = cobj;
					cobj.attr({width:BD.landscape.cardCompareWidth, height:BD.landscape.cardCompareHeight});					
					ct++;
				}
			}else if(cardsize == 5){
				wgap = parseInt((BD.landscape.cardDisplayArea.width/2 - BD.landscape.cardCompareWidth * 2 - gap)/2);
				hgap2 = parseInt((BD.landscape.cardDisplayArea.height - BD.landscape.cardCompareHeight * 2 - gap) / 2);
				tleft = BD.landscape.cardDisplayArea.left + ((user=='user1')?wgap:parseInt(BD.landscape.cardDisplayArea.width/2 + wgap));
				var ct = 0;
				for(var k in cardobjs){
					var cobj = cardobjs[k];
					if(user == 'user1'){
						if(ct < 3){
							ml = tleft;
							mt = hgap + (BD.landscape.cardCompareHeight + gap) * ct;
						}else{
							ml = tleft + BD.landscape.cardCompareWidth + gap;
							mt = hgap2 + (BD.landscape.cardCompareHeight + gap) * (ct - 3);
						}
					}else{
						if(ct < 2){
							ml = tleft;
							mt = hgap2 + (BD.landscape.cardCompareHeight + gap) * ct;
						}else{
							ml = tleft + BD.landscape.cardCompareWidth + gap;
							mt = hgap + (BD.landscape.cardCompareHeight + gap) * (ct - 2);
						}
					}
					cobj.css({left:ml + 'px', top:mt + 'px', width:BD.landscape.cardCompareWidth + 'px', height:BD.landscape.cardCompareHeight + 'px', display:'block'});
					cobj.css({'border':'solid 2px #aaa', 'border-radius':'5px', '-moz-border-radius':'5px', '-webkit-border-radius':'5px'});
					BD.displaying[BD.displaying.length] = cobj;
					cobj.attr({width:BD.landscape.cardCompareWidth, height:BD.landscape.cardCompareHeight});
					ct++;
				}
			}			
		}

	};

	BD.resizeSideBar = function(){

		BD.reposSideBar();

		for(var k in BD.users){
			for(var m in BD.users[k]){
				var hero = BD.users[k][m];
				hero.decorateFeatures();
			}
		}

		$userhead1Obj = $('#sidebar1_userhead');
		$userhead2Obj = $('#sidebar2_userhead');
		$user1_herocard1Obj = $('#sidebar1_hero' + BD.userHeroOrderIds.user1[0]);
		$user1_herocard2Obj = $('#sidebar1_hero' + BD.userHeroOrderIds.user1[1]);
		$user1_herocard3Obj = $('#sidebar1_hero' + BD.userHeroOrderIds.user1[2]);
		$user2_herocard1Obj = $('#sidebar2_hero' + BD.userHeroOrderIds.user2[0]);
		$user2_herocard2Obj = $('#sidebar2_hero' + BD.userHeroOrderIds.user2[1]);
		$user2_herocard3Obj = $('#sidebar2_hero' + BD.userHeroOrderIds.user2[2]);

		$userhead1Obj.css({'left':BD[BD.orientation].user1.pos[0] + 'px', 'top':BD[BD.orientation].user1.pos[1] + 'px'});
		$userhead2Obj.css({'left': BD[BD.orientation].user2.pos[0] + 'px', 
								'top':BD[BD.orientation].user2.pos[1] + 'px'});
		$user1_herocard1Obj.css({'left': BD[BD.orientation].user1_hero1.pos[0] + 'px', 'top':BD[BD.orientation].user1_hero1.pos[1] +'px'});
		$user1_herocard2Obj.css({'left': BD[BD.orientation].user1_hero2.pos[0] + 'px', 'top':BD[BD.orientation].user1_hero2.pos[1] + 'px'});
		$user1_herocard3Obj.css({'left': BD[BD.orientation].user1_hero3.pos[0] + 'px', 
						'top':BD[BD.orientation].user1_hero3.pos[1]+'px'});
		$user2_herocard1Obj.css({'left': BD[BD.orientation].user2_hero1.pos[0] + 'px', 
							'top':BD[BD.orientation].user2_hero1.pos[1] + 'px'});
		$user2_herocard1Obj.attr('idx', 1);
		$user2_herocard1Obj.attr('heroid', BD.userHeroOrderIds.user2[0]);
		$user2_herocard2Obj.css({'left': BD[BD.orientation].user2_hero2.pos[0] + 'px', 
							'top':BD[BD.orientation].user2_hero2.pos[1] + 'px'});
		$user2_herocard2Obj.attr('idx', 2);
		$user2_herocard2Obj.attr('heroid', BD.userHeroOrderIds.user2[1]);
		$user2_herocard3Obj.css({'left': BD[BD.orientation].user2_hero3.pos[0] + 'px', 
							'top':BD[BD.orientation].user2_hero3.pos[1] + 'px'});
		$user2_herocard3Obj.attr('idx', 3);
		$user2_herocard3Obj.attr('heroid', BD.userHeroOrderIds.user2[2]);

	};

	BD.drawUserHead = function(headimg, w, h, idx, orientation) {
		var sidebarid = '#sidebar' +idx+ '_userhead_' + orientation;
		ym_head_handler.drawUserHead(headimg, w, h, $(sidebarid), 
			function(){
				BD.preload();
			}
		);
	};

	BD.drawHeroCard = function(img, w, h, level, idx, heroid, heroidx, orientation){
		var sidebarid = '#sidebar'+idx+'_hero'+heroid+'_' + orientation;
		var pos = BD[orientation]['user' + idx + '_hero' + heroidx].pos;
		ym_head_handler.drawHeroCard(img, w, h, level, $(sidebarid), 
			function(){
				BD.decorateHeroCard($(sidebarid), pos);
			}
		);
	};

	BD.initPortraitUI = function(){
		if(BD.orientation == 'portrait'){
			BD.screenW = $(document.body).width();
			BD.screenH = $(document.body).height();			
		}else{
			BD.screenW = $(document.body).height();
			BD.screenH = $(document.body).width();
		}
		BD.portrait.barWidth = BD.screenW;
		BD.portrait.barHeight = parseInt(BD.screenH * BD.borderBarRate);

		BD.portrait.userHeadSize = parseInt(BD.portrait.barHeight * BD.headRate);

		var rate = BD.portrait.barHeight / BD.heroCardOriHeight;
		BD.portrait.heroCardHeight = BD.portrait.barHeight;
		BD.portrait.heroCardWidth = parseInt(BD.heroCardOriWidth * rate);

		var w = BD.portrait.userHeadSize + BD.headHeroGap + BD.portrait.heroCardWidth * 3 + BD.portraitPreWidth;
		if(w > BD.screenW){
			var w0 = BD.screenW - BD.headHeroGap - BD.portrait.userHeadSize - BD.portraitPreWidth;
			rate = w0 / (w - BD.headHeroGap - BD.portrait.userHeadSize - BD.portraitPreWidth);
			BD.portrait.heroCardWidth = parseInt(BD.portrait.heroCardWidth * rate);
			BD.portrait.heroCardHeight = parseInt(BD.portrait.heroCardHeight * rate);
		}

		var heroCardScale = BD.portrait.heroCardHeight / BD.heroCardOriHeight;
		BD.portrait.heroCardScale = heroCardScale;

		BD.reposSideBar('portrait');

		var kidx = 0;
		for(var k in BD.resourcesName){
			kidx ++;
			var key = BD.resourcesName[k];
			var targetUser = BD.initContent[key];
			BD.drawUserHead(targetUser.headimg, BD.portrait.userHeadSize, BD.portrait.userHeadSize, kidx, 'portrait');
			for(var i=0; i<3; i++){
				var heroidx = i + 1;
				var hero = targetUser['game_hero' + heroidx];
				BD.drawHeroCard(hero.pic, BD.portrait.heroCardWidth, BD.portrait.heroCardHeight, hero.level, kidx, hero.id, heroidx, 'portrait');
			}
		}


	};

	BD.initLandscapeUI = function(){
		if(BD.orientation == 'landscape'){
			BD.screenW = $(document.body).width();
			BD.screenH = $(document.body).height();			
		}else{
			BD.screenW = $(document.body).height();
			BD.screenH = $(document.body).width();
		}
		BD.landscape.barWidth = parseInt(BD.screenW * BD.borderBarRate);
		BD.landscape.barHeight = BD.screenH;

		BD.landscape.userHeadSize = parseInt(BD.landscape.barWidth * BD.headRate);

		var rate = BD.landscape.barWidth / BD.heroCardOriWidth;
		BD.landscape.heroCardWidth = BD.landscape.barWidth;
		BD.landscape.heroCardHeight = parseInt(BD.heroCardOriHeight * rate);

		var h = BD.landscape.userHeadSize + BD.headHeroGap + BD.landscape.heroCardHeight * 3;
		if(h > BD.screenH){
			var h0 = BD.screenH - BD.headHeroGap - BD.landscape.userHeadSize;
			rate = h0 / (h - BD.headHeroGap - BD.landscape.userHeadSize);
			BD.landscape.heroCardWidth = parseInt(BD.landscape.heroCardWidth * rate);
			BD.landscape.heroCardHeight = parseInt(BD.landscape.heroCardHeight * rate);
		}

		var userheadScale = BD.landscape.userHeadSize / BD.userHeadOriSize;

		var heroCardScale = BD.landscape.heroCardWidth / BD.heroCardOriWidth;

		BD.landscape.heroCardScale = heroCardScale;
		BD.reposSideBar('landscape');


		var kidx = 0;
		for(var k in BD.resourcesName){
			kidx ++;
			var key = BD.resourcesName[k];
			var targetUser = BD.initContent[key];
			BD.drawUserHead(targetUser.headimg, BD.landscape.userHeadSize, BD.landscape.userHeadSize, kidx, 'landscape');
			for(var i=0; i<3; i++){
				var heroidx = i + 1;
				var hero = targetUser['game_hero' + heroidx];
				BD.drawHeroCard(hero.pic, BD.landscape.heroCardWidth, BD.landscape.heroCardHeight, hero.level, kidx, hero.id, heroidx, 'landscape');
			}
		}

	};
};

var initall = function(){
	<?php 
       echo 'var initContent = '.$content.';';
       echo 'var gameId = "'.$gameId.'";';
       echo 'var gameUserId = "'.$gameUserId.'";';
    ?>
	var board = new ym_game13_playboard(initContent, gameId, gameUserId);
	board.init();
};

$(function(){
	document.body.onload = initall;
});

</script>
</html>