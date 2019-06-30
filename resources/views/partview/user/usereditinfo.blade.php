@extends('layouts.publish')
@section('title',  '基本信息')
@section('formrange')
<?php 
    $oneform = Publish::form('/user/edituser', '基本信息');
    $oneform->addComp(array(
            'name' => 'display_name',
            'validators' => array(Publish::$VAL_REQUIRED),
            'placeholder' => '请输入昵称',
            'defaultValue' => $user->display_name, ));
    $oneform->addComp(array(
            'name' => 'avatar',
            'type' => Publish::$TP_PIC,
            'validators' => array(Publish::$VAL_REQUIRED),
            'defaultValue' => str_replace('@64h_64w_1e_1c', '', $user->avatar),
            'label' => '上传头像', 'imguploadLimit' => 1, '请上传头像', ));
    $oneform->addComp(array(
            'name' => 'background',
            'type' => Publish::$TP_PIC,
            'defaultValue' => $user->background,
            'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '上传个性背景图片', 'imguploadLimit' => 1, '请上传个性背景图片', ));
    $oneform->addComp(array('name' => 'age',
            'type' => Publish::$TP_DATE,
            'label' => '生日',
            'defaultValue' => $default['age'],
            'errorMessage' => '', ));
    $oneform->addComp(array('name' => 'sex',
            'type' => Publish::$TP_RADIO,
            'validators' => array(Publish::$VAL_REQUIRED),
            'selectables' => $attr['20002']['code'],
            'selectlabels' => $attr['20002']['name'],
            'label' => '性别',
            'isRequired' => true,
            'defaultValue' => $default['sex'],
            'errorMessage' => '请选择性别', ));
    $oneform->addComp(array('name' => 'merage',
            'type' => Publish::$TP_COMBO,
            'validators' => array(Publish::$VAL_REQUIRED),
            'selectables' => $attr['20003']['code'],
            'selectlabels' => $attr['20003']['name'],
            'label' => '婚姻状态',
            'isRequired' => true,
            'defaultValue' => $default['merge'], 'errorMessage' => '请选择婚姻状态', ));
    $oneform->addComp(array('name' => 'record',
            'type' => Publish::$TP_COMBO,
            'validators' => array(Publish::$VAL_REQUIRED),
            'selectables' => $attr['20004']['code'],
            'selectlabels' => $attr['20004']['name'],
            'label' => '学历',
            'isRequired' => true,
            'defaultValue' => $default['record'], 'errorMessage' => '请选择学历', ));
    $oneform->addComp(array('name' => 'job',
            'type' => Publish::$TP_COMBO,
            'validators' => array(Publish::$VAL_REQUIRED),
            'selectables' => $attr['20005']['code'],
            'selectlabels' => $attr['20005']['name'],
            'label' => '职业',
            'isRequired' => true,
            'defaultValue' => $default['position'], 'errorMessage' => '请选择职业', ));
    $oneform->addComp(array('name' => 'text',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED,
            Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 100)), ),
            'label' => '签名',
            'defaultValue' => $default['sign'],
            'placeholder' => '请填写您的签名，不能超过100个字...',
            'errorMessage' => '请填写您的签名', ));
    $oneform->addComp(array('name' => 'rules',
            'type' => Publish::$TP_RULE,
            'label' => '设定技能',
            'defaultValue' => '',
            'jsonUrl' => '/json/userskill', ));
    $oneform->addComp(array('name' => 'id',
            'type' => Publish::$TP_HIDDEN,
            'defaultValue' => $id, ));

    $oneform->end();
?>
<script type="text/javascript" src="/js/ym_rule.js"></script>
@section('scriptrange')
	var imgcount1 = $('input[name="avatar"]').attr('imgcount')*1;
	var maxcount1 = $('input[name="avatar"]').attr('maxcount')*1;
	var imgcount2 = $('input[name="background"]').attr('imgcount')*1;
	var maxcount2 = $('input[name="background"]').attr('maxcount')*1;
	if(imgcount1 == maxcount1){
		$('#avatar_addbtn').css('display','none');
	}
	if(imgcount2 == maxcount2){
		$('#background_addbtn').css('display','none');
	}
	var rule_skill = new Rule();
	rule_skill.setting.defaultValue = "{{$default['userSkillLevel']}}";
	rule_skill.setting.maxSkillLevel = '2';
    rule_skill.setting.defaultValidate = 'integer,numberLimit!1!2';
    rule_skill.setting.defaultErrormsg = '输入1到2之间的整数';
    rule_skill.setting.skillShow = 'spread';
    @if($default['userSkillLevel'])
	rule_skill.addExistTag();
	@endif
@stop
@stop
