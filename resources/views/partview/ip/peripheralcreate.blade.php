@extends('layouts.publish')
@section('title',  '发布周边产品')
@section('formrange')
<?php

$oneform = Publish::form('/peripheral/create', '发布周边产品');
$oneform->addComp(array(
    'name' => 'title',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '请输入周边名称...', ));
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '请输入周边说明...', ));
$oneform->addComp(array(
    'name' => 'ip_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $ip_id, ));
$oneform->addComp(array(
    'name' => 'image',
    'imguploadLimit' => 1,
    'type' => Publish::$TP_PIC, ));
$oneform->end();
?>
@stop