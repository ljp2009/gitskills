@extends('layouts.publish')
@section('title',  '修改密码')
@section('formrange')
<div class='am-container' style="padding:0 2rem">
@if (Session::has('error'))
    {{ trans(Session::get('reason')) }}
@endif
<?php
    $oneform = Publish::form('/password/reset', '修改密码');
    $oneform->addComp(array('name' => 'token',
            'type' => Publish::$TP_HIDDEN,
            'defaultValue' => $token, ));
    $oneform->addComp(array(
        'name' => 'email',
        'validators' => array(Publish::$VAL_PHONEORMAIL),
        'placeholder' => trans('auth.name'),
        'defaultValue' => old('email'), ));
    $oneform->addComp(array(
        'name' => 'password',
        'validators' => array(Publish::$VAL_REQUIRED, Publish::createOneValidator(Publish::$VAL_LIMIT, array(6, 12))),
        'type' => Publish::$TP_PASSWORD,
        'label' => '新密码',
        'placeholder' => trans('auth.pwd'),
        'errorMessage' => '输入6-12位的密码', ));
    $oneform->addComp(array(
        'name' => 'password_confirmation',
        'validators' => array(Publish::$VAL_REQUIRED, Publish::createOneValidator(Publish::$VAL_LIMIT, array(6, 12))),
        'type' => Publish::$TP_PASSWORD,
        'label' => '确认密码',
        'placeholder' => trans('auth.pwd'),
        'errorMessage' => '输入6-12位的确认密码', ));
    $oneform->end('确定');
?>
</div>
@stop