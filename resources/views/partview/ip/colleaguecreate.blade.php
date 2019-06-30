@extends('layouts.publish')
@section('title',  '发布同人作品')
@section('formrange')
<?php

$oneform = Publish::form('/colleague/create', '发布同人作品');
$oneform->addComp(array(
    'name' => 'title',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '请输入作品名称...', ));
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '请输入作品说明...', ));
$oneform->addComp(array(
    'name' => 'ip_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $ip_id, ));
$oneform->addComp(array(
    'name' => 'cover',
    'type' => Publish::$TP_PIC,
    'imguploadLimit' => 1, ));
$oneform->addComp(array(
        'name' => 'show',
        'type' => Publish::$TP_TEXT,
        'label' => '显示的链接文本',
        'placeholder' => '显示给用户看的链接文本',
        'errorMessage' => '请填写显示给用户看的链接文本', ));
$oneform->addComp(array(
        'name' => 'link',
        'type' => Publish::$TP_URL,
        'label' => '显示的链接地址',
        'placeholder' => '输入您要链接的完整地址',
        'errorMessage' => '请输入您要链接的完整地址', ));
$oneform->end();
?>
@section('scriptrange')
	$('input[name="show"]').blur(function(){
		var show = $.trim($(this).val());
		var validate="required,url";
		if(show){
			$('input[name="link"]').attr('validate',validate);
		}else{
			$('input[name="link"]').attr('validate','');
		}
	});
@stop
@stop