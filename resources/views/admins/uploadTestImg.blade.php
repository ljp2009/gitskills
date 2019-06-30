@extends('layouts.publish')
@section('title',  '上传测试图片')
@section('content')
	@parent
	@section('formrange')
	<?php 
        $oneform = Publish::form('/', '上传测试图片');

        $oneform->addComp(array('name' => 'test', 'type' => Publish::$TP_PIC, 'label' => '上传测试图片', 'imguploadLimit' => 100));
        $oneform->end();
    ?>
	@stop
	@section('scriptrange')
		var vc = $('#test_value');
		vc.attr('nameSeed','test/');
		_ym_useFileName = true;
	@stop
@stop