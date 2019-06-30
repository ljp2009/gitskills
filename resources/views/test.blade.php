@extends('layouts.block')
@section('title')
    测试页面
@stop
@section('content')
  @section('serverload')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'测试页面'])
<link rel="stylesheet" href="/css/formpage.css" />
<pre>
<?php
    $aa = 'aaaaa ddd ff s';
    $x = stripos($aa, 'a');
    if($x === false){
    var_dump('no');
    }else{
    var_dump($x);
    }
?>
<img src="http://img.umeiii.com/default.jpg">
<a href="http://www.baidu.com">mmmmmmmmmmM</a>
</pre>
<form action="/test" method="post" >
<input name='v' value ='a' type="hidden">
<input type="submit" value='提交'>
</form>

  @stop
  @parent
@stop
@section('runScript')
@stop
