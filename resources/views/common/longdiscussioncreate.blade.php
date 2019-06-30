@extends('layouts.publish')
@section('title',  '创建长评论')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<?php 
    if ($resource == 'ip') {
        $tail = 'long';
        $topic = '创建长评论';
        $limit = Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 65535));
        $msg = '请输入您的评论';
    }

    $oneform = Publish::form('/common/discuss/createlong', $topic);
    $oneform->addComp(array(
            'name' => 'title',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED),
            'placeholder' => '请输入评论标题...', ));
    $oneform->addComp(array('name' => 'content', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED, $limit),
            'label' => '评论内容', 'placeholder' => $msg, ));
    $oneform->addComp(array('name' => 'resource', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $resource));
    $oneform->addComp(array('name' => 'resourceId', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $resourceID));
    $oneform->addComp(array('name' => 'url', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $url));
    $oneform->end();
?>
</div>
@stop