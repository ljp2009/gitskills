@extends('layouts.publish')
@section('title',  '发布任务作品')
@section('formrange')
<?php 
    $oneform = Publish::form('/task/createprod', '发布任务作品');
    $oneform->addComp(array(
            'name' => 'text',
            'type' => Publish::$TP_TEXTAREA,
            'validators' => array(Publish::$VAL_REQUIRED),
            'placeholder' => '您的任务作品介绍', ));
    $oneform->addComp(array(
            'name' => 'image',
            'type' => Publish::$TP_PIC,
            'label' => '上传图片(限5张)',
            'imguploadLimit' => 5, ));
    $oneform->addComp(array(
            'name' => 'id',
            'type' => Publish::$TP_HIDDEN,
            'defaultValue' => $id, ));
    $oneform->end();
?>

@stop