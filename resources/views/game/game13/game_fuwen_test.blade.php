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

	} 

    @media all and (orientation:portrait) { 
		body { 
		   background:url(/game13/pic/background/portrait);
		   filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
		   -moz-background-size:100% 100%;
		   background-size:100% 100%;		 
		} 
	} 
	.fuwen_character_container{
		margin:0px;
		width:100%;
		height: 30px;
	}

	.fuwen_word{
		font-size: 16px;
		font-weight: bold;
		position: fixed;
		z-index: 100;
	}
	.fuwen_bar{
	    border:solid #aaa 1px;
		border-radius:5px;
		-webkit-border-radius:5px;
		-moz-border-radius:5px;
		overflow:hidden;
		height:17px;
		width: 270px;
		position: fixed;
		z-index: 100;
		text-align: left;
	}
	.fuwen_bar_inner_assist{
		width:40px;
		height:15px;
		background:#ff0;
		border-radius:4px;
		-webkit-border-radius:4px;
		-moz-border-radius:4px;		
	}
	.fuwen_bar_inner_bar{
		width:20px;
		height:15px;
		border-radius:4px;
		-webkit-border-radius:4px;
		-moz-border-radius:4px;		
		margin-top: -15px;
	}
	.fuwen_action{
		font-size: 20px;
		font-weight: bold;
		position:fixed;
		z-index:99;
	}

	.fuwen_canvas {
		position:fixed;
		z-index:98;
		top:200px;
		width:100%;
	}

	.fuwen_background{
		-moz-border-radius:50%;
		-webkit-border-radius:50%;
		border-radius:50%;
		position:fixed;
		z-index:99;		
		display:none;
	}
	.fuwen_bg_attack{
		-webkit-animation: attack_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: attack_anim 1.5s ease-in-out infinite alternate;
		animation: attack_anim 1.5s ease-in-out infinite alternate; 
	}
	.fuwen_bg_strength{
		-webkit-animation: strength_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: strength_anim 1.5s ease-in-out infinite alternate;
		animation: strength_anim 1.5s ease-in-out infinite alternate; 
	}
	.fuwen_bg_mana{
		-webkit-animation: mana_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: mana_anim 1.5s ease-in-out infinite alternate;
		animation: mana_anim 1.5s ease-in-out infinite alternate; 
	}
	.fuwen_bg_speed{
		-webkit-animation: speed_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: speed_anim 1.5s ease-in-out infinite alternate;
		animation: speed_anim 1.5s ease-in-out infinite alternate; 
	}
	.fuwen_bg_protect{
		-webkit-animation: protect_anim 1.5s ease-in-out infinite alternate;
		-moz-animation: protect_anim 1.5s ease-in-out infinite alternate;
		animation: protect_anim 1.5s ease-in-out infinite alternate; 
	}
	.fuwen_img{
		position:fixed;
		z-index:100;		
	}		
	   @-webkit-keyframes attack_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #3a0;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #3a0;
		  }
		}
		/*glow for mozilla*/
		@-moz-keyframes attack_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #3a0;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #3a0;
		  }
		}

	   @-webkit-keyframes strength_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #e26f32;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #e26f32;
		  }
		}
		/*glow for mozilla*/
		@-moz-keyframes strength_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #e26f32;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #e26f32;
		  }
		}

	   @-webkit-keyframes mana_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #96e0fd;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #96e0fd;
		  }
		}
		/*glow for mozilla*/
		@-moz-keyframes mana_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #96e0fd;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #96e0fd;
		  }
		}

	   @-webkit-keyframes speed_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #fae601;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #fae601;
		  }
		}
		/*glow for mozilla*/
		@-moz-keyframes speed_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #fae601;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #fae601;
		  }
		}

	   @-webkit-keyframes protect_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #f97be6;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #f97be6;
		  }
		}
		/*glow for mozilla*/
		@-moz-keyframes protect_anim {
		  from {
			box-shadow: 0px 0px 150px 25px #f97be6;
		  }
		  to {
			box-shadow: 0px 0px 100px 15px #f97be6;
		  }
		}

  </style>
</head>
<body>
</body>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript">
var game_fireball = function(canvasObj, fuwenImg, fuwenBg){
	var FB = this;
	FB.canvasObj = canvasObj;
	FB.particles = {};

	FB.landscapeImg = new Image();
	FB.landscapeImg.src = "/game13/pic/background/landscape";
	FB.portraitImg = new Image();
	FB.portraitImg.src = "/game13/pic/background/portrait";

	FB.topMargin = 200;
	FB.fuwenBorderMargin = 50;

	FB.fuwenImg = fuwenImg;
	FB.fuwenBackground = fuwenBg;

	FB.setSize = function(w, h){
		FB.width = w;
		var sz = FB.width;
		FB.height = h;

		var pos = h - FB.topMargin;

		if(sz > pos){
			sz = pos;
		}

		FB.canvasObj.width = w;
		FB.canvasObj.height = h;
		FB.canvasObj.clientWidth = w;
		FB.canvasObj.clientHeight = h;
		FB.y = Math.floor(pos/2 + FB.topMargin) ;
		FB.R = Math.floor((sz - 50)/2);
		FB.x = Math.floor(w/2) + FB.R ;

		if(w > h){
			FB.orientation = 'landscape';
		}else{
			FB.orientation = 'portrait';
		}

		FB.fuwenX = Math.floor(w/2) - FB.R + FB.fuwenBorderMargin;
		FB.fuwenY = FB.y - FB.R + FB.fuwenBorderMargin;
		FB.fuwenW = (FB.R - FB.fuwenBorderMargin) * 2;
		FB.fuwenH = FB.fuwenW;

		var bgW = Math.floor(FB.fuwenW * 0.8);
		var off = Math.floor((FB.fuwenW - bgW)/2);

		FB.fuwenBackground.css({'left': ( FB.fuwenX + off) + 'px', 'top': ( FB.fuwenY + off) + 'px', 'width':bgW + 'px', 'height':bgW + 'px'});

		FB.fuwenImg.css({'left':FB.fuwenX + 'px', 'top':FB.fuwenY + 'px'});
		FB.fuwenImg[0].width = FB.fuwenW;
		FB.fuwenImg[0].height = FB.fuwenH;
	};

	FB.pindex = 0;
	FB.newParticle = function(x,y,r,o,c,xv,yv,rv,ov){

	    FB.particles[FB.pindex] = {
	      index: FB.pindex, 
	      x: x,
	      y: y,
	      r: r,
	      o: o,
	      c: c,
	      xv: xv,
	      yv: yv,
	      rv: rv,
	      ov: ov
	    };

	    FB.pindex ++;
	};

	FB.R = 200;
	FB.STEP = 160;

	FB.initx = 0, FB.inity = 0;

	FB.countPosFunc = function(fireball){
	  fireball.ct ++;
	  var sf = fireball.ct % FB.STEP;
	  var cor = fireball.startAng - 2 * Math.PI * sf / FB.STEP;
	  var offy = FB.R * Math.sin(cor);
	  var offx = FB.R * Math.cos(cor);
	  fireball.x = FB.initx- (FB.R - offx);
	  fireball.y = FB.inity - offy;
	};

	FB.fireballs = [];

	FB.findex = 0;

	FB.newFireball = function(x,y,xv,yv,ang,cl){
	    if(typeof(cl)=='undefined'){
	      cl = 'red';
	    }
	    FB.fireballs[FB.findex] = {
	      index: FB.findex,
	      x: x,
	      y: y,
	      xv: xv,
	      yv: yv,
	      zx: x,
	      zy: y,
	      ct: 0,
	      color:cl,
	      startAng:ang
	    };

	    FB.findex ++ ;
	};

	FB.removeFireBall = function(cl) {
	    var len = FB.fireballs.length;
	    var colors = [];
	    for(var i=0 ; i<len; i++){
	      var clr = FB.fireballs[i].color;
	      if(clr != cl){
	      	colors.push(clr);
	      }
	    }
	    len = colors.length;
	    var unitang = 2 * Math.PI / len;

	    FB.fireballs = [];

	    FB.findex = 0;

	    for(var i=0; i<len; i++){
	        var curang = 2 * Math.PI - i * unitang;
	        var offy = FB.R * Math.sin(curang);
	        var offx = FB.R * Math.cos(curang);      
	        var x0 = FB.initx - (FB.R - offx);
	        var y0 = FB.inity - offy;
	        FB.newFireball(x0, y0, 0, 0, curang, colors[i]);
	    }		
	};

	FB.addNewFireBall = function(cl){
	    var len = FB.fireballs.length;
	    var colors = [];
	    for(var i=0 ; i<len; i++){
	      colors[i] = FB.fireballs[i].color;
	    }
	    colors[len] = cl;
	    len ++;
	    var unitang = 2 * Math.PI / len;

	    FB.fireballs = [];

	    FB.findex = 0;

	    for(var i=0; i<len; i++){
	        var curang = 2 * Math.PI - i * unitang;
	        var offy = FB.R * Math.sin(curang);
	        var offx = FB.R * Math.cos(curang);      
	        var x0 = FB.initx - (FB.R - offx);
	        var y0 = FB.inity - offy;
	        FB.newFireball(x0, y0, 0, 0, curang, colors[i]);
	    }
	};

	FB.drawFireballs = function(){
		var ctx = FB.canvasObj.getContext('2d');
		ctx.setTransform(1, 0, 0, 1, 0, 0);
	    ctx.globalCompositeOperation = 'source-over';
	    ctx.globalAlpha = 1;
      	ctx.fillStyle = '#fff';
      	ctx.fillRect(0, 0, FB.width, FB.height);
	    ctx.translate(FB.x, FB.y);

	    ctx.drawImage((FB.orientation=='portrait')?FB.portraitImg:FB.landscapeImg, 
	      			-1 * FB.x, -1 * FB.y, FB.width, FB.height);
	    
	    if(FB.fireballs.length>0){

	      //ctx.globalCompositeOperation = 'lighter';
	      for (var i in FB.particles) {
	          var p = FB.particles[i];
	          ctx.beginPath();
	          ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
	          ctx.globalAlpha = p.o;
	          ctx.fillStyle = p.c;
	          ctx.fill();
	      }

	      for (var i in FB.particles) {
	          var p = FB.particles[i];
	          p.x += p.xv;
	          p.y += p.yv;
	          p.r += p.rv;
	          p.o += p.ov;
	          if (p.r < 0) delete FB.particles[p.index];
	          if (p.o < 0) delete FB.particles[p.index];
	      }

	      for (var i in FB.fireballs) {

	          f = FB.fireballs[i];
	          var particleColor = f.color;

	          var numParticles = Math.sqrt(f.xv * f.xv + f.yv * f.yv) / 5;
	          if (numParticles < 1) numParticles = 1;
	          var numParticlesInt = Math.ceil(numParticles);
	          var numParticlesDif = numParticles / numParticlesInt;
	          for (var j = 0; j < numParticlesInt; j++) {

	              FB.newParticle(
	                f.x - f.xv * j / numParticlesInt,
	                f.y - f.yv * j / numParticlesInt,
	                7,
	                numParticlesDif,
	                particleColor,
	                Math.random() * 0.9 - 0.3,
	                Math.random() * 0.9 - 0.3,
	                -0.3,
	                -0.05 * numParticlesDif
	              );
	          }

	          FB.countPosFunc(f);
	      }
	    }
	};
};


var fuwen_set = function(){
	var FW = this;

	FW.data = {
		attack:{value:0, color:'#3a0', word: '攻', working:false, pending:[]},
		strength:{value:0, color:'#e26f32', word: '体', working:false, pending:[]},
		mana:{value:0, color:'#96e0fd', word:'技', working:false, pending:[]},
		speed:{value:0, color:'#fae601', word:'速', working:false, pending:[]},
		protect:{value:0, color:'#f97be6', word:'防', working:false, pending:[]}
	};

	FW.bgChgCount = 0;
	FW.datatypes = [];

	FW.init = function(){
		var html = '';
		var ct = 0;
		for(var k in FW.data){
			FW.datatypes.push(k);
			var one = FW.data[k];
			var wl = 5;
			var wt = 3 + 30 * ct;
			var bl = 30;
			var bt = 7 + 30 * ct;
			var al = 30 + 270 +5;
			var at = 30 * ct;
			var idbar = k + "_bar";
			var idassist = k + "_assist";
			var idplus = k + "_plus";
			var idminus = k + "_minus";
			html += '<div class="fuwen_character_container"><div class="fuwen_word" style="left:'+
						wl+'px; top:'+wt+'px; color:' +one.color+ '">'+ one.word +'&nbsp;</div><div class="fuwen_bar" style="left:' + 
						bl+'px; top:'+bt+'px"><div class="fuwen_bar_inner_assist" id="'+idassist+'" style="left:' + 
						bl+'px; top:'+bt+'px"></div><div class="fuwen_bar_inner_bar" id="'+ idbar +'" style="left:' + 
						bl+'px; top:'+bt+'px; background:' +one.color+ '"></div></div><div class="fuwen_action" style="left:'+al+'px; top:' +at+ 'px;">'+
						'<a id="'+idplus+'"><i class="am-icon-plus"></i></a><a id="'+idminus+'" style="padding-left:5px"><i class="am-icon-minus"></i></a>' + 
						'</div></div>';
			ct ++;
		}
		html += '<canvas class="fuwen_canvas"></canvas><div class="fuwen_background"></div><img class="fuwen_img"/>';
		$(document.body).append(html);
		for(var k in FW.data){
			var one = FW.data[k];
			var idbar = k + "_bar";
			var idassist = k + "_assist";
			var idplus = k + "_plus";
			var idminus = k + "_minus";
			one.bar = $('#' + idbar);
			one.assist = $('#' + idassist);
			var initwidth = one.value * 30 + 'px';
			one.bar.css('width', initwidth);
			one.assist.css('width', initwidth);
			one.plus = $('#' + idplus);
			one.minus = $('#' + idminus);
			one.plus.attr('owntype', k);
			one.minus.attr('owntype', k);
			one.plus.on('click', function(){
				FW.plus($(this).attr('owntype'));
			});

			one.minus.on('click', function(){
				FW.minus($(this).attr('owntype'));
			});
		}

		FW.fuwenBackground = $('.fuwen_background');
		FW._changeFWBg();
		FW.fuwenImg = $('.fuwen_img');
		FW.fuwenImg[0].onload = function(){
			FW.fuwenBackground.css('display', 'block');
		};
		FW.fuwenImg.attr('src', '/game13/pic/fuwen');
		FW.canvas = $('.fuwen_canvas');
		FW.screenW = $(document.body).width();
		FW.screenH = $(document.body).height();

		var canw = FW.screenW; var canh = FW.screenH;
		FW.canvas.css({width:canw + 'px', left:'0px', top:'0px', height:canh + 'px'});

		FW.fireball = new game_fireball(FW.canvas[0], FW.fuwenImg, FW.fuwenBackground);
		FW.fireball.setSize(canw, canh);
	};

	FW._changeFWBg = function(){
		var len = FW.datatypes.length;
		var classNm = 'fuwen_bg_' + FW.datatypes[FW.bgChgCount%len];
		FW.bgChgCount ++;

		FW.fuwenBackground.attr('class', 'fuwen_background ' + classNm);

		setTimeout(function(){
			FW._changeFWBg();
		}, 6000);
	};

	FW._handleRest = function(one){
		if(one.pending.length == 0){
			one.working = false;
		}else{
			var func = one.pending.shift();
			func();
		}
	};

	FW.plus = function(tp){
		var one = FW.data[tp];
		if(one.value == 9){
			return;
		}
		one.value = one.value + 1;
		if(one.value == 9){
			FW.fireball.addNewFireBall(one.color);
		}
		var w = one.value * 30 + 'px';

		var func = function(){
			FW._plusWork(w, one);
		};

		if(one.working){
			one.pending.push(func);
		}else{
			func();
		}
	};

	FW._plusWork = function(w, one){
		one.working = true;
		one.assist.animate({'width':w}, 300, function(){
			one.bar.animate({'width':w}, 100, function(){
				FW._handleRest(one);
			});
		});
	};

	FW.minus = function(tp){
		var one = FW.data[tp];
		if(one.value == 0){
			return;
		}
		if(one.value == 9){
			FW.fireball.removeFireBall(one.color);
		}
		one.value = one.value - 1;
		var w = one.value * 30 + 'px';
		
		var func = function(){
			FW._minusWork(w, one);
		};
		if(one.working){
			one.pending.push(func);
		}else{
			func();
		}
	};

	FW._minusWork = function(w, one){
		one.working = true;
		one.bar.animate({'width':w}, 300, function(){
			one.assist.animate({'width':w}, 100, function(){
				FW._handleRest(one);
			});
		});
	};

	FW.drawCanvas = function(){
		FW.fireball.drawFireballs();

		var func = function(){
			FW.drawCanvas();
		};

		requestAnimationFrame(func);
	};
};



var initall = function(){
	var fw = new fuwen_set();
	fw.init();
	var func = function(){
		fw.drawCanvas();
	};
	requestAnimationFrame(func);
};

$(function(){
	document.body.onload = initall;
});

</script>
</html>