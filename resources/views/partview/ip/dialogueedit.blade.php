@extends('layouts.publish')
@section('title',  '编辑经典台词')
@section('formrange')
<?php

$oneform = Publish::form('/ipdialogue/edit', '编辑经典台词');
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '台词内容',
    'defaultValue' => $model->textPart,
    'placeholder' => '请输入台词...', ));
$oneform->addComp(array(
    'name' => 'role',
    'type' => Publish::$TP_TEXT,
    'label' => '原作者',
    'defaultValue' => $model->rolePart,
    'placeholder' => '台词的原作者...', ));
$oneform->addComp(array(
    'name' => 'id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $model->id, ));
$oneform->end();
?>
@stop
