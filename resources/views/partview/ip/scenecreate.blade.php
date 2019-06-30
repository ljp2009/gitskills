@extends('layouts.publish')
@section('title',  '发布经典场景')
@section('formrange')
<?php
$oneform = Publish::form('/ipscene/create', '发布经典场景');
$oneform->addComp(array(
    'name' => 'text',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '场景内容',
    'placeholder' => '请描述场景...', ));
$oneform->addComp(array(
    'name' => 'ip_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $ip_id, ));
$oneform->addComp(array(
    'name' => 'imageStr',
    'type' => Publish::$TP_HIDDEN,
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'image',
    'type' => Publish::$TP_PIC,
    'label' => '上传场景图片',
    'imguploadLimit' => 1, ));
$oneform->end();
?>
@stop
