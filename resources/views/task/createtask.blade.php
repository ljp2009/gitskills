@extends('layouts.publish')
@section('title',  '发布任务')
@section('formrange')
<?php

$oneform = Publish::form('/task/create', '发布任务');
$oneform->addComp(array(
    'name' => 'title',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '填写任务名称', ));
$oneform->addComp(array(
    'name' => 'skill_type',
    'type' => Publish::$TP_COMBO,
    'selectables' => $skValues,
    'selectlabels' => $skLabels,
    'label' => '任务类型',
    'isRequired' => true,
    'defaultValue' => '9000',
    'errorMessage' => '请选择类型', ));
$oneform->addComp(array(
    'name' => 'skill_level',
    'type' => Publish::$TP_COMBO,
    'selectables' => array(0, 1, 2, 3, 4, 5),
    'selectlabels' => array('无限制', '爱好', '达人', '职业', '大神', '王者'),
    'label' => '用户技能限制',
    'isRequired' => true,
    'defaultValue' => '0',
    'errorMessage' => '请选择任务类型', ));
$oneform->addComp(array(
    'name' => 'credit_level',
    'type' => Publish::$TP_COMBO,
    'selectables' => array(0),
    'selectlabels' => array('无限制'),
    'label' => '用户信誉限制',
    'isRequired' => true,
    'defaultValue' => '0',
    'errorMessage' => '请选择任务类型', ));
$oneform->addComp(array(
    'name' => 'amount',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_NUMBER),
    'label' => '任务佣金',
    'placeholder' => '输入报酬', ));
$oneform->addComp(array(
    'name' => 'is_crowdfunding',
    'type' => Publish::$TP_COMBO,
    'selectables' => array(1, 0),
    'selectlabels' => array('众筹', '非众筹'),
    'label' => '是否众筹',
    'isRequired' => true,
    'defaultValue' => 0,
    'errorMessage' => '请选择是否众筹', ));
$oneform->addComp(array(
    'name' => 'delivery_date',
    'type' => Publish::$TP_DATE,
    'validators' => array(Publish::$VAL_REQUIRED),
    'label' => '交付日期',
    'defaultValue' => '', ));
$oneform->addComp(array(
    'name' => 'intro',
    'label' => '任务介绍',
    'type' => Publish::$TP_TEXTAREA,
    'validators' => array(Publish::$VAL_REQUIRED),
    'placeholder' => '详细介绍你的任务...', ));
$oneform->addComp(array(
    'name' => 'image',
    'type' => Publish::$TP_PIC,
    'label' => '上传图片(限5张)',
    'imguploadLimit' => 5, ));
$oneform->addComp(array(
    'name' => 'tag',
    'type' => Publish::$TP_TAG,
    'label' => '选择标签',
    'defaultValue' => '',
    'jsonUrl' => '/json/tasktags', ));
$oneform->addComp(array(
    'name' => 'evaluation_type',
    'type' => Publish::$TP_COMBO,
    'label' => '选择评审方式',
    'selectables' => array(1, 2, 3),
    'selectlabels' => array('PK模式', '约定模式', '第三方评估模式'),
    'defaultValue' => '',
    'jsonUrl' => '/json/tasktags', ));
$oneform->end();
?>
    @section('scriptrange')
    //<script type="text/javascript">

    @stop
@stop