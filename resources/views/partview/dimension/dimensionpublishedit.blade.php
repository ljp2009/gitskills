@extends('layouts.publish')
@section('title',  '编辑资源')
@section('formrange')
<?php 
    $oneform = Publish::form('/dimension/publishedit', '编辑资源');
    $oneform->addComp(array('name' => 'text',
            'type' => Publish::$TP_TEXTAREA,
            'validators' => array(Publish::$VAL_REQUIRED,
            Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 100)),
    ), 'label' => '次元介绍',
            'defaultValue' => $model->text,
            'placeholder' => '说说您的想法...',
            'errorMessage' => '说说您的想法', ));
    $oneform->addComp(array('name' => 'image',
            'type' => Publish::$TP_PIC,
            'label' => '图片',
            'defaultValue' => $model->image,
            'imguploadLimit' => 6, ));
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $model->id));

    $oneform->end();
?>

@section('scriptrange')
	var imgcount = $('input[name="image"]').attr('imgcount')*1;
	var maxcount = $('input[name="image"]').attr('maxcount')*1;
	if(imgcount == maxcount){
		$('#image_addbtn').css('display','none');
	}
@stop
@stop