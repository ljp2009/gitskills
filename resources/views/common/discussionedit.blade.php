@extends('layouts.publish')
@section('title',  '编辑评论')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<?php 

    $oneform = Publish::form('/common/discuss/edit', '编辑评论');

    $oneform->addComp(array('name' => 'content',
            'type' => Publish::$TP_TEXTAREA,
            'defaultValue' => $model->text,
            'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '评论内容',
            'placeholder' => '请输入您的评论...', ));
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $model->id));
    $oneform->addComp(array('name' => 'url', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $url));
    $oneform->end();
?>
</div>
@stop