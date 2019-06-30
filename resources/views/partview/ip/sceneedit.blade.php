@extends('layouts.publish')
@section('title',  '编辑经典场景')
@section('formrange')
<?php
$oneform = Publish::form('/ipscene/edit', '编辑经典场景');
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '场景内容',
    'defaultValue' => $model->text,
    'placeholder' => '请描述场景...', ));
$oneform->addComp(array(
    'name' => 'id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $model->id, ));
$oneform->addComp(array(
    'name' => 'image',
    'type' => Publish::$TP_PIC,
    'defaultValue' => $model->image,
    'label' => '上传场景图片',
    'imguploadLimit' => 1, ));
$oneform->end();
?>
@stop
