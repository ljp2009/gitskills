@extends('layouts.publish')
@section('title',  '发布资源')
@section('formrange')
<?php 
    $oneform = Publish::form('/dimension/publishcreate', '发帖子');
    $oneform->addComp(array('name' => 'text',
        'type' => Publish::$TP_TEXTAREA,
        'validators' => array(Publish::$VAL_REQUIRED,
    ), 'label' => '内容', 'placeholder' => '请填写内容...', 'errorMessage' => '请填写内容', ));
    $oneform->addComp(array('name' => 'image', 'type' => Publish::$TP_PIC, 'label' => '图片', 'imguploadLimit' => 6));
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $id));
    $oneform->addComp(array('name' => 'act_id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $act_id));
    $oneform->end();
?>

@section('scriptrange')
	
@stop
@stop
