<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="format-detection" content="telephone=no" />
  <title>
      【有妹社区】验证邮件
  </title>
</head>
<body style="background-color:#f5f5f9;width:100%">
    <h1>欢迎注册有妹社区</h1>
    <hr />
    <div style="width:100%;padding:15px">
        以下是您的验证码。
        <br />
        <span style="font-size:25px;color:#ef51ac">{{$token}}</span>
    </div>
</body>
</html>
