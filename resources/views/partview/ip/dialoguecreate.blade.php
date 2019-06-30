@extends('layouts.publish')
@section('title',  '发布经典台词')
@section('formrange')
<?php

$oneform = Publish::form('/ipdialogue/create', '发布经典台词');
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '台词内容',
    'placeholder' => '请输入台词...', ));
$oneform->addComp(array(
    'name' => 'role',
    'type' => Publish::$TP_TEXT,
    'label' => '原作者',
    'placeholder' => '台词的原作者...', ));
$oneform->addComp(array(
    'name' => 'ip_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $ip_id, ));
$oneform->end();
?>
@stop
