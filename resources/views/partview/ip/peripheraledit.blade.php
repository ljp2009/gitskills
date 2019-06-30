@extends('layouts.publish')
@section('title',  '编辑周边产品')
@section('formrange')
<?php

$oneform = Publish::form('/peripheral/edit', '编辑周边产品');
$oneform->addComp(array(
    'name' => 'title',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED),
    'defaultValue' => $model->title,
    'placeholder' => '请输入周边名称...', ));
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'defaultValue' => $model->text,
    'placeholder' => '请输入周边说明...', ));
$oneform->addComp(array(
    'name' => 'id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $model->id, ));
$oneform->addComp(array(
    'name' => 'image',
    'type' => Publish::$TP_PIC,
    'defaultValue' => $model->image,
    'imguploadLimit' => 1, ));
$oneform->end();
?>
@section('scriptrange')
	var imgcount = $('input[name="image"]').attr('imgcount')*1;
	var maxcount = $('input[name="image"]').attr('maxcount')*1;
	if(imgcount == maxcount){
		$('#image_addbtn').css('display','none');
	}
@stop
@stop