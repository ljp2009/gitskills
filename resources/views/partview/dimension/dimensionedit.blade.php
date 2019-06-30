@extends('layouts.publish')
@section('title',  '编辑次元')
@section('formrange')
<?php
    $oneform = Publish::form('/dimension/edit', '编辑次元');
    $oneform->addComp(array('name' => 'name',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED,
            Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 20)),
    ), 'label' => '次元名称',
            'defaultValue' => $model->name,
            'placeholder' => '请填写您要发布的次元名称...',
            'errorMessage' => '请填写您要发布的次元名称,不超过20个字', ));

    $oneform->addComp(array('name' => 'header',
            'type' => Publish::$TP_PIC,
            'label' => '次元图片',
            'defaultValue' => $model->header,
            'imguploadLimit' => 1, ));
    $oneform->addComp(array(
        'name' => 'tag',
        'type' => Publish::$TP_TAG,
        'jsonUrl' => '/dimension/tags',
        'label' => '点击标签可以移除', ));
    $oneform->addComp(array('name' => 'text',
            'type' => Publish::$TP_TEXTAREA,
            'validators' => array(Publish::$VAL_REQUIRED,
            Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 100)),
    ), 'label' => '次元介绍',
            'defaultValue' => $model->text,
            'placeholder' => '请填写您的次元介绍，不能超过100个字...',
            'errorMessage' => '请填写您的次元介绍', ));

    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $model->id));

    $oneform->end();
?>

@section('scriptrange')
//<script type="text/javascript">
	$('input[name="show"]').blur(function(){
		var show = $.trim($(this).val());
		var validate="required,url";
		if(show){
			$('input[name="link"]').attr('validate',validate);
		}else{
			$('input[name="link"]').attr('validate','');
		}
	});
   _ym_attachTag = new AttachTag();
   _ym_attachTag.bind({'defaultJsonUrl':'/dimension/tags',
       'defaultValue':'{{$model->tagCodes}}'});
   _ym_attachTag.addExistTag();

	var imgcount = $('input[name="header"]').attr('imgcount')*1;
	var maxcount = $('input[name="header"]').attr('maxcount')*1;
	if(imgcount == maxcount){
		$('#header_addbtn').css('display','none');
	}
@stop
@stop
