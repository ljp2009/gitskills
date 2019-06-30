@extends('layouts.block')
@section('content')
	@section('serverLoad')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$model->name])
    <div class="ym_cm_alertpage_icon">
    <span></span>
    </div>
    <div class="ym_cm_alertpage_action">
    <span></span>
    <div>
    <div class="ym_cm_alertpage_ad"></div>
	@stop
	@section('runScript')
	@stop
@stop
