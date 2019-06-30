<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
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
  </head>
  <body>
  <label for="user_id">用户ID</label>
  <select id="user_id">
    <?php
        foreach ($users as $one) {
            echo '<option value="'.$one.'">用户'.$one.'</option>';
        }
    ?>
  </select>
  <button type="button" class="am-btn am-btn-primary" id="start_btn">开始游戏</button>
  <button type="button" class="am-btn am-btn-primary" id="test_post">test</button>
  <p style='display:none'></p>
  <form id="hideform" action="/game13/wip/play" method="post">
    <input name="_token" type="hidden" value="{{csrf_token()}}"/>
    <input name="gameId" type="hidden"/>
    <input name="gameUserId" type="hidden"/>
  </form>
  </body>
  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/js/amazeui.min.js"></script>
  <script type="text/javascript">
  		var timestamp = 0;
      function post(result){
        $('input[name="gameId"]').val(result.gameId);
        $('input[name="gameUserId"]').val(result.gameUserId);
        $('#hideform').submit();
      }
  		function startGame(){
  			timestamp = new Date().getTime();
  			$.getJSON('/game13/trigger/' + $('#user_id')[0].value, function(result){
  				if(result == 'false'){
  					checkGameStarted();
  				}else{
            post(result);
  				}
  			});

  			$('#start_btn').css('display', 'none');
  			$('p').css('display', 'block');
  			$('p').text('正在匹配玩家...');
  		}

  		function checkGameStarted() {
  			var cur = new Date().getTime();
  			if(cur - timestamp > 3000){
  				$('#start_btn').css('display', 'block');
  				$('p').css('display', 'none');
  				return;
  			}
  			$.getJSON('/game13/check/' + userid, function(result){
  				if(result == 'false'){
  					checkGameStarted();
  				}else{
  					post(result);
  				}
  			});
  		}

  		$('#start_btn').on('click', function(){
  			startGame();
  		});

      $('#test_post').on('click', function(){
        $.post('/game13/wip/test', {'_token':$('meta[name="csrf-token"]').attr('content'), 
          'data':JSON.stringify({a:1, b:1, c:{a:1, b:1}})}, 
          function(v){
            alert(v);
          });
      });
  </script>
</html>
