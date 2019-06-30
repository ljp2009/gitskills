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
    Test evaluate
  </title>
  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="stylesheet" href="/assets/css/amazeui.min.css">
  <style type="text/css">
    .nextbtn{
      position:fixed;
      left: 120px;
      top: 0px;
    }
  </style>
</head>
<body style="font-size:10px">
  <button onclick="reset()">Reset</button>
  <button onclick="navigate()">Navigate</button>
  <button onclick="next()" class="nextbtn">Next</button>
</body>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript">
  <?php 
     echo 'var initContent = '.$content.';';
     echo 'var gameId = "'.$gameId.'";';
     echo 'var gameUserId = "'.$gameUserId.'";';
  ?>
  var curstage = 0;
  var curround = 0;
  var curdata = 0;
  var curticket = '';
  function reset() {
    $.get('/game13/wip/test', function(v){
      alert(v);
    });
  }
  function navigate(){
    window.document.location = '/game13/testPlay';
  }

  function next(){
    if(curstage == 1){
      var submitdata = curdata.data[0];
      $.post('/game13/wip/submitcards', {
        '_token':$('meta[name="csrf-token"]').attr('content'), gameUserId:gameUserId, gameId:gameId,
        data:JSON.stringify(submitdata)
      }, function(v, status){
        if(status == 'success'){
           if(v == 'false' || v===false){
            commonNext();
          }else{
            dealWithData(JSON.parse(v));
          }         
        }else{
          setTimeout(function(){next();}, 500);
        }

      })
    }else{
      commonNextStage();
    }
  }

  function commonNextStage(){
    var url = '/game13/wip/nextstage/'+ gameId + '/' + gameUserId ;
    $.getJSON(url, function(v, status){
      if(status == 'success'){
       if(v == 'false'||v ===false){
        commonNext();
      }else{
        dealWithData(v);
      }       
    }else{
      setTimeout(function(){commonNextStage();}, 500);
    }

    });
  }
  function commonNext(){
    var url = '/game13/wip/next/' + gameId + '/' + gameUserId ;
    if(curticket != ''){
      url = url + '/' + curticket;
    }
    $.getJSON(url, function(v, status){
      if(status=='success'){
         if(v == 'false'||v ===false){
          commonNext();
        }else{
          dealWithData(v);
        }       
      }else{
        commonNext(); 
      }

    });
  }

  function dealWithData (data){
    curstage = data.stage;
    curround = data.round;
    curdata = data;
    curticket = data.ticket;
    var html = '<div>Stage:' + data.stage + ' Round:' + data.round + '</div>' +
              '<div><p>' + JSON.stringify(data) + '</p></div>' + '<hr/>';
    $(document.body).append(html);
  }

  dealWithData(initContent);
</script>
</html>