@extends('layouts.publish')
@section('title',  '资源大厅推荐')
@section('formrange')
<?php 
    $oneform = Publish::form('/reshall/addhallbanner', '资源大厅banner推荐');
    $oneform->addComp(array(
            'name' => 'image',
            'type' => Publish::$TP_PIC,
            'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '上传banner图片', 'imguploadLimit' => 1, '请上传banner图片', ));
    $oneform->addComp(array('name' => 'description',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED,
            Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 30)), ),
            'label' => '描述',
            'placeholder' => '请填写描述，不能超过30个字...',
            'errorMessage' => '请填写描述', ));
    $oneform->addComp(array('name' => 'url',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED,
                    Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 30)), ),
            'label' => '对应链接',
            'placeholder' => '请填写对应链接，如/ip/1',
            'errorMessage' => '请填写对应链接', ));
    $oneform->end();
?>

@section('scriptrange')
	
@stop
@stop