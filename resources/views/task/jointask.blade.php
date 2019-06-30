<!--参加任务-->
@extends('layouts.publish')
@section('title',  '参加任务')
@section('formrange')
<?php

$oneform = Publish::form('/task/join', '参加任务');
$oneform->addComp(array('name' => 'description', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED,
    Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 20)),
), 'label' => '申请描述', 'placeholder' => '请填写您的申请描述...', 'errorMessage' => '请填写您的申请描述'));

//     $oneform->addComp(array('name'=>'header', 'type'=>Publish::$TP_PIC,'label'=>'次元图片','imguploadLimit'=>1));

$oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $id));

$oneform->end('确定申请');
?>
@stop
@section('scriptrange')
	$('#search').click(function(){

	});
@stop