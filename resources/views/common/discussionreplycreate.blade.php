@extends('layouts.publish')
@section('title',  '回复评论')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))

<?php 
    $label = '回复 '.$discussion->user->display_name.'<br>';
    $oneform = Publish::form('/common/discuss/reply', $label);
?>
<span style="color:gray;">
	{{$discussion->text}}
</span>
<?php
    $oneform->addComp(array('name' => 'content', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '回复内容', 'placeholder' => '请输入您的回复...', ));
    $oneform->addComp(array('name' => 'referenceId', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $referenceId));
    $oneform->addComp(array('name' => 'responseTo', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $discussion->id));
    $oneform->addComp(array('name' => 'url', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $url));
    $oneform->end();
?>

@stop