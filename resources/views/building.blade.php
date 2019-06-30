@extends('layouts.block')
@section('title','页面建设中')
@section('content')
	@section('serverload')
		@include('partview.headerbar',['left'=>'back', 'center'=>'logo', 'right'=>'home'])
    <style type="text/css">
body{
    background:#fff;
}
.container{
    width:100%;
    text-align:center;
    padding:0;
    margin:0;
    margin-bottom:70px;
}
.img{
    display:block;
    width:150px;
    margin-top:25%;
    margin-left:auto;
    margin-right:auto;
}
.label{
    display:block;
    margin-top:50px;
}

</style>
    <div class="container">
        <img src="imgs/pic1.png"  class="img"/>
        <label class="label">
        页面建设中……
        </label>
    </div>
	@stop
    @parent
    @section('runScript')
    @stop
@stop
