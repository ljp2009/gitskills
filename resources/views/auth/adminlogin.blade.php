<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>登录有妹社区管理</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="alternate icon" type="image/png" href="/i/favicon.png">
  <link rel="stylesheet" href="/assets/css/amazeui.min.css">
  <style>
    .header { text-align: center; }
    .header h1 { font-size: 200%; color: #333; margin-top: 30px; }
    .header p { font-size: 14px; }
  </style>
	<script src="/assets/js/jquery.min.js"></script>
	<script src="/js/common.js"></script>
</head>
<body>
<div class="header">
  <div class="am-g">
    <h1>有妹社区管理后台</h1>
  </div>
  <hr />
</div>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h3>登录</h3>
    <hr> <br> <br> 
    <input type="hidden" id="redirectCode" value="A0000000100000000" />
    <label for="email">邮箱:</label>
    <input type="email" id="uname" value="">
    <br>
    <label for="password">密码:</label>
    <input type="password" id="password" value="">
    <br>
    <label id="err"></label>
    <div class="am-cf">
    <input type="button" value="登 录"
       class="am-btn am-btn-primary am-btn-sm am-fl" onclick="submitData()">
    </div>
    <hr>
  </div>
</div>
<script type="text/javascript"> 
function submitData(){
    var uname        = $('#uname').val();
    var pwd          = $('#password').val();
    var redirectCode = $('#redirectCode').val();
    var token        = $.ymFunc.getToken();
    if(uname == '' || pwd == ''){
        $('#err').val('请填写用户名和密码。');
        return;
    }
    $.post('www.baidu.com',{
        'uname'        : '1',
        'password'     : password,
        'redirectCode' : redirectCode,
        '_token'       : token
    }, function(data){
        if(data.res){
            $.ymFunc.goTo(data.url);   
        }else{
            $('#err').val(data.info);
        }
    }).error(function(e){
        alert(e);
    });
}
</script>
</body>
