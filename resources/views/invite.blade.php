@extends('layouts.city')
@section('title', '邀请')
@section('formrange')
<?php

$models = array('1' => '等级一',
 '2' => '等级二',
 '3' => '等级三',
 '4' => '等级四', );
$citySel = array();
$oneform = Publish::form('/invite/publish', '发送一个邀请');
$oneform->addComp(array(
    'name' => 'inviteNum',
    'type' => Publish::$TP_TEXT,
    'defaultValue' => '',
    'label' => '邀请人数',
    'validators' => array(Publish::$VAL_REQUIRED,
        Publish::$VAL_NUMBER, Publish::$VAL_LIMIT, ),
));
$oneform->addComp(array(
    'name' => 'inviteCity',
    'type' => Publish::$TP_CITY,
    'defaultValue' => $citySel,
    'label' => '地区',
    'validators' => array(Publish::$VAL_REQUIRED),
));

$oneform->addComp(array(
    'name' => 'inviteSkillName',
    'type' => Publish::$TP_COMBO,
    'selectitems' => $skills,
    'label' => '技能',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'inviteSkillLevel',
    'type' => Publish::$TP_COMBO,
    'selectitems' => $models,
    'label' => '技能等级',
    'validators' => array(Publish::$VAL_REQUIRED),
));
$oneform->addComp(array(
    'name' => 'inviteCreditLevel',
    'type' => Publish::$TP_COMBO,
    'defaultValue' => '1',
    'selectitems' => $models,
    'label' => '信誉等级',
    'validators' => array(Publish::$VAL_REQUIRED),
));

$oneform->end('发送邀请', 'navbar');
?>

@stop
@stop
@section('scriptrangecity')
$YN_VALIDATOR.validators['inviteNumber']=
    [function(v){
        return parseInt(v) > 0;
    },'邀请人数需要大于0.'];


$('input[name="inviteNum"]').addClass('ym-publish-field');
$('input[name="inviteNum"]').attr('validate', 'required,integer,inviteNumber');

$('input[name="inviteCity"]').addClass('ym-publish-field');
$('input[name="inviteCity"]').attr('validate','required');
@stop


