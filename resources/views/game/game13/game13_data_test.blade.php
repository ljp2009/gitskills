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
  	第一武道会数据准备
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
  <style type="text/css">
	.abs{
		position:fixed;
		z-index: 99;
	}
  </style>
</head>
<body>
	<button type="button" class="am-btn am-btn-primary" id="submit_btn">开始游戏</button>
	<button type="button" class="am-btn am-btn-secondary" id="equal_btn">平均分配</button>
	<div class="container"></div>
	<form id="hideform" action="/game13/wip/play" method="post">
	    <input name="_token" type="hidden" value="{{csrf_token()}}"/>
	    <input name="gameId" type="hidden"/>
	    <input name="gameUserId" type="hidden"/>
	    <input name="mode" value="test" type="hidden"/>
	</form>
</body>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript">
var DataPrepare = function(){
	var DB = this;
	DB.container = $('.container');
	DB.data = {
		margin:10,
		user_levels:{
			"1":{point:120}, "2":{point:122}, "3":{point:124}, "4":{point:126}, "5":{point:128}, "6":{point:130}, "7":{point:132}, "8":{point:134}, "9":{point:136}, "10":{point:138}, "11":{point:140}, "12":{point:142}, "13":{point:144}, "14":{point:146}, "15":{point:148}, "16":{point:150}, "17":{point:152}, "18":{point:154}, "19":{point:156}, "20":{point:158}, "21":{point:160}, "22":{point:162}, "23":{point:164}, "24":{point:166}, "25":{point:168}, "26":{point:170}, "27":{point:172}, "28":{point:174}, "29":{point:176}, "30":{point:180}
		},
		users:['user1', 'user2'],
		feature_enum:{'ti':'体', 'gong':'攻', 'ji':'技', 'su':'速', 'fang':'防'}
	};
	DB.data.width = $(document.body).width() - DB.data.margin * 2;
	DB.data.height = $(document.body).height() - DB.data.margin * 2;
	DB.data.left = 10;
	DB.data.top = 40;
	DB.curlevel = {};
	for(var i=0; i<DB.data.users.length; i++){
		DB.curlevel[DB.data.users[i]] = "1";
	}

	DB.preserved = {};
    DB.post = function(result){
        $('input[name="gameId"]').val(result.gameId);
        $('input[name="gameUserId"]').val(result.gameUserId);
        $('#hideform').submit();
    }

	DB.evalPoint = function(v){
		if(v <= 3)
			return 1;
		if(v <= 6)
			return 2;
		if(v <= 7)
			return 3;
		if(v <= 8)
			return 4;
		return 5;
	};

	DB.maxPoint = function(point, start){
		if(typeof(start) == 'undefined'){
			start = 0;
		}
		for(var i=start + 1; i <= 9; i++){
			var pt = DB.evalPoint(i);
			point -= pt;
			if(point <= 0){
				return [i - 1, point + pt];
			}
		}
		return [9, point];
	};

	DB.equalDispatch = function(username){
		var level = $('#' + username + '_sel').val();
		var point = DB.data.user_levels[level].point;
		var avgpoint = parseInt(point / 3);
		var rest = point - avgpoint * 3;

		var eachpoint = parseInt(avgpoint / 5);
		var maxpoint = DB.maxPoint(eachpoint);

		var results = [];
		for(var i=0; i<15; i++){
			results[i] = maxpoint[0];
			rest += maxpoint[1];
		}
		if(rest > 0){
			for(var i=0; i<15; i++){
				var tmp = DB.maxPoint(rest, results[i]);
				results[i] = tmp[0];
				rest = tmp[1];
				if(rest == 0){
					break;
				}
			}			
		}
		var idx = 0;
		for(var i=0; i<3; i++){
			var heroname = 'hero' + (i + 1);
			for(var nk in DB.data.feature_enum){
				var skey = username + '_'+ heroname +'_' + nk;
				$('#' + skey).html(results[idx]);
				idx ++;
			}
		}
		$('#' + username + '_point').html(rest);
	};

	DB.plus = function(username, heroname, feature){
		var pointobj = $('#' + username + '_point');
		var point = parseInt(pointobj.html());
		var key = username + '_' + heroname + '_' + feature;
		var obj = $('#' + key);
		var fp = parseInt(obj.html());

		if(fp == 9){
			return;
		}
		fp += 1;
		var thep = DB.evalPoint(fp);
		if(point < thep){
			return;
		}
		point -= thep;
		obj.html(fp);
		pointobj.html(point);
	};

	DB.startGame = function(){
		$('#submit_btn').attr('disabled', 'disabled');
		var finalresult = {};
		for(var s=0; s<2; s++){
			var username = DB.data.users[s];
			var data = {};
			var existpoint = $('#' + username + '_point').html();
			data.point = existpoint;
			data.level = $('#' + username + '_sel').val();
			data.wins = parseInt($('#' + username + '_win').val());
			data.hero = {};
			for(var i=0; i<3; i++){
				var heroname = 'hero' + (i + 1);
				data.hero[heroname] = {};
				for(var nk in DB.data.feature_enum){
					var skey = username + '_'+ heroname +'_' + nk;
					data.hero[heroname][nk] = $('#' + skey).html();
				}
				data.hero[heroname]['level'] = $('#' + username + '_'+ heroname +'_level').val();
			}
			finalresult[username] = data;
		}

		var datastr = JSON.stringify(finalresult);

        $.post('/game13/prepare', {'_token':$('meta[name="csrf-token"]').attr('content'), 
          'data':datastr}, 
          function(v){
          	 DB.userids = JSON.parse(v);
          	 DB.trigerNext(DB.userids);
          });		
	};

	DB.trigerNext = function(users, idx){
		if(typeof(idx)=='undefined'){
			idx = 0;
		}
  		$.getJSON('/game13/trigger/' + users[idx], function(result){
  				if(result == 'false'){
  					DB.trigerNext(users, 1);
  				}else{
            		DB.post(result);
  				}
  		});		
	};

	DB.minus = function(username, heroname, feature){
		var pointobj = $('#' + username + '_point');
		var point = parseInt(pointobj.html());
		var key = username + '_' + heroname + '_' + feature;
		var obj = $('#' + key);
		var fp = parseInt(obj.html());

		if(fp == 0){
			return;
		}
		var thep = DB.evalPoint(fp);
		point += thep;
		pointobj.html(point);

		fp -= 1;
		obj.html(fp);
	};

	DB.reset = function(username, level) {
		if(typeof(level) == 'undefined'){
			level = DB.curlevel[username];
		}
		$('#' + username + '_point').html(DB.data.user_levels[level].point);
		for(var i=0; i<3; i++){
			var heroname = 'hero' + (i + 1);
			for(var nk in DB.data.feature_enum){
				var skey = username + '_'+ heroname +'_' + nk;
				$('#' + skey).html("0");
			}
		}		
	};

	DB.changeTo = function(username, level){
		var oldkey = username + '_' + DB.curlevel[username];
		var data = {};
		var existpoint = $('#' + username + '_point').html();
		data.point = existpoint;

		for(var i=0; i<3; i++){
			var heroname = 'hero' + (i + 1);
			data[heroname] = {};
			for(var nk in DB.data.feature_enum){
				var skey = username + '_'+ heroname +'_' + nk;
				data[skey] = $('#' + skey).html();
			}
		}

		DB.preserved[oldkey] = data;
		DB.curlevel[username] = level;
		var newkey = username + '_' + level;

		if(typeof(DB.preserved[newkey]) == 'undefined'){
			DB.reset(username, level);
		}else{
			data = DB.preserved[newkey];
			$('#' + username + '_point').html(data.point);
			for(var i=0; i<3; i++){
				var heroname = 'hero' + (i + 1);
				for(var nk in DB.data.feature_enum){
					var skey = username + '_'+ heroname +'_' + nk;
					$('#' + skey).html(data[skey]);
				}
			}			
		}
	};

	DB.init = function(){
		$('#submit_btn').on('click', function(){DB.startGame();});
		$('#equal_btn').on('click', function(){
			for(var i=0; i<2; i++){
				DB.equalDispatch(DB.data.users[i]);
			}
		});
		var html = '';
		var unitwidth = parseInt(DB.data.width / 3);
		for(var i=0; i<2; i++){
			var username = DB.data.users[i];
			var t = DB.data.top + i * 180; var l = DB.data.left;
			html += '<div class="abs" style="left:'+l+'px; top:'+t+'px">'
					+ '用户名：' + username + '&nbsp;&nbsp;级别：<select user="'+username+'" id="' + username + '_sel">';
			for(var key in DB.data.user_levels){
				html += '<option value="'+ key +'">等级' + key + '</option>'; 
			} 	
			html += '</select>&nbsp;点数：<span class="user_point" id="' +username+ '_point">'
			    + DB.data.user_levels["1"].point + '</span>&nbsp;&nbsp;';
			html += '<button user="'+username+'" id="'+username+'_reset">重置</button>&nbsp;&nbsp;';
			html += '最近胜轮：<select id="' + username + '_win">';
			for(var m=0; m<=10; m++){
				html += '<option value="' + m + '">' + m + '</option>';
			}
			html += '</select>';
			html += '</div>';

			var herolevel = ['青铜', '白银', '黄金', '白金', '钻石'];
			//hero
			for(var j=0; j<3; j++){
				var heroname = 'hero' + (j + 1);
				var ol = l + j * unitwidth;
				var ot = t + 25;
				html += '<div class="abs" style="font-weight:bold; left:'+ ol +'px; top:' +ot+ 'px">' + heroname ;
				var lvlid = username + '_'+ heroname +'_level';
				html += '&nbsp;&nbsp;<select id="' + lvlid + '">';
				for(var m=0; m<5; m++){
					html+='<option value="' + (m + 1) + '">' + herolevel[m] + '</option>';
				}
				html += '</select>';
				html += '</div>';

				ot += 25;
				for(var nk in DB.data.feature_enum){
					html += '<div class="abs" style="font-weight:bold; left:'+ ol +'px; top:' +ot+ 'px">' + DB.data.feature_enum[nk] ;
					var skey = username + '_'+ heroname +'_' + nk;
					html += '：<span id="' + skey +'" style="font-weight:bold">0</span>&nbsp;';
					html += '<button user="' +username+ '" hero="'+heroname+'" feature="'+ nk +'" id="' + skey + '_plus">+</button>';
					html += '<button user="' +username+ '" hero="'+heroname+'" feature="'+ nk +'" id="' + skey + '_minus">-</button>';
					html += '</div>';
					ot += 25;
				}
			}
		}
		DB.container.append(html);
		for(var i=0; i<2; i++){
			var username = DB.data.users[i];
			$('#' + username + '_sel').change(function(){
				var obj = $(this);
				var umn = obj.attr('user');
				DB.changeTo(umn, obj.val());
			});
			$('#' + username + '_reset').on('click', function(){
				var unm = $(this).attr('user');
				DB.reset(unm);
			});
			for(var j=0; j<3; j++){
				var heroname = 'hero' + (j + 1);
				for(var nk in DB.data.feature_enum){
					var skey = username + '_'+ heroname +'_' + nk;
					$('#' + skey + '_plus').on('click', function(){
						var obj = $(this);
						DB.plus(obj.attr('user'), obj.attr('hero'), obj.attr('feature'));
					});					
					$('#' + skey + '_minus').on('click', function(){
						var obj = $(this);
						DB.minus(obj.attr('user'), obj.attr('hero'), obj.attr('feature'));
					});					
				}
			}

		}
	};

};

var initall = function(){
	var prepare = new DataPrepare();
	prepare.init();
};

$(function(){
	document.body.onload = initall;
});

</script>
</html>