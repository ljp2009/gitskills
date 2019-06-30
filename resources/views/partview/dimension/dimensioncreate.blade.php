@extends('layouts.publish')
@section('title',  '发布次元')
@section('formrange')
<?php
    $oneform = Publish::form('/dimension/create', '发布次元');
    $oneform->addComp(array(
        'name' => 'name',
        'type' => Publish::$TP_TEXT,
        'validators' => array(Publish::$VAL_REQUIRED, Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 20))),
        'label' => '次元名称',
        'placeholder' => '请填写您要发布的次元名称,不超过20个字...',
        'errorMessage' => '请填写您要发布的次元名称,不超过20个字', ));
    $oneform->addComp(array(
        'name' => 'text',
        'type' => Publish::$TP_TEXTAREA,
        'validators' => array(Publish::$VAL_REQUIRED, Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 100))),
        'label' => '次元介绍',
        'placeholder' => '请填写您的次元介绍，不能超过100个字...',
        'errorMessage' => '请填写您的次元介绍', ));
    $oneform->addComp(array(
        'name' => 'header',
        'type' => Publish::$TP_PIC,
        'label' => '次元图片',
        'imguploadLimit' => 1, ));
    //$oneform->addComp(array('name'=>'attrcode[]', 'type'=>Publish::$TP_LIST,
    //  'isRequired'    => true,
    //  'selectables'   => $attrCode,
    //  'selectlabels'  => $attrArr,'label' => '请选择作品属性',
    //	'isRequired'    => true, 'defaultValue'=>'0','errorMessage'=>'请选择作品属性'));
    $oneform->addComp(array(
        'name' => 'tag',
        'type' => Publish::$TP_TAG,
        'jsonUrl' => '/dimension/tags',
        'label' => '点击标签可以移除', ));

    $oneform->addComp(array(
        'name' => 'attrbute',
        'type' => Publish::$TP_HIDDEN,
        'defaultValue' => '4000201', ));
    $oneform->addComp(array(
            'name' => 'id',
            'type' => Publish::$TP_HIDDEN,
            'defaultValue' => $id, ));
    $oneform->end();
?>

@section('scriptrange')
	$('input[name="show"]').blur(function(){
		var show = $.trim($(this).val());
		var validate="required,url";
		if(show){
			$('input[name="link"]').attr('validate',validate);
		}else{
			$('input[name="link"]').attr('validate','');
		}
	});
@stop
@stop
