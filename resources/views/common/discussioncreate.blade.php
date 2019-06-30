@extends('layouts.publish')
@section('title',  '创建评论')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<?php 
    $tail = 'short';
    $topic = '创建评论';
    $msg = '请输入您的评论';
    $limit = '';
    if ($resourcename == 'ip') {
        $limit = Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 240));
        $msg = '请输入240字以内的评论';
        $topic = '创建短评';
        if (!$isshort) {
            $tail = 'long';
            $topic = '创建长评论';
            $limit = Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 2400));
            $msg = '请输入您的评论';
        }
    }

    $oneform = Publish::form('/common/discuss/create/'.$tail, $topic);
    $oneform->addComp(array('name' => 'content', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED, $limit),
            'label' => '评论内容', 'placeholder' => $msg, ));
    $oneform->addComp(array('name' => 'resource', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $resourcename));
    $oneform->addComp(array('name' => 'resourceId', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $resourceid));
    $oneform->addComp(array('name' => 'referenceId', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $referenceid));
    $oneform->addComp(array('name' => 'url', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $url));
    $oneform->end();
?>
</div>
@stop