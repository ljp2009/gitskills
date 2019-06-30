@extends('layouts.publish')
@section('title',  '添加新作品')
@section('formrange')
<?php

$oneform = Publish::form('/ip/create', '添加新作品');
?>
<input type="hidden" name='isAjaxPost' value="true" />
<?php
$oneform->addComp(array(
    'name' => 'ipname',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED),
    'errorMessage' => '请输入作品名称',
    'placeholder' => '请输入作品名称', ));
$oneform->addComp(array(
    'name' => 'iptype',
    'type' => Publish::$TP_COMBO,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '请选择分类',
    'selectitems' => ['cartoon' => '动漫', 'story' => '小说', 'light' => '轻小说', 'game' => '游戏'],
    'defaultValue' => 'cartoon', ));
$oneform->addComp(array(
    'name' => 'intro',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'errorMessage' => '请输入作品说明',
    'placeholder' => '请输入作品说明...', ));

$oneform->addComp(array(
    'name' => 'cover',
    'type' => Publish::$TP_PIC,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '上传封面',
    'imguploadLimit' => 1, ));
$oneform->addComp(array(
    'name' => 'attr',
    'type' => Publish::$TP_DYNAMICATTR,
    'label' => '', ));
$oneform->addComp(array(
    'name' => 'tag',
    'type' => Publish::$TP_TAG,
    'label' => '点击标签可以移除', ));
$oneform->end();
?>

@section('scriptrange')
//<script>
	$('select[name="iptype"]').on('change',function(){
		$('#tagContainer').children().remove();
		$('#tagsList').val('');
		$('#select-attr-modal-content').html('');
		$('#select-tag-modal-content').html('');
		$('#dynamicAttrContiner').children().remove();
		$('#attrsList').val('');
	});
    function postForm(){
        $YN_VALIDATOR.submitForm();
    }
@stop
@stop
