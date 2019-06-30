@extends('layouts.publish')
@section('title',  '发布任务')
@section('formrange')
<?php
$oneform = Publish::form('/task/create', '编辑任务');
$oneform->addComp(array('name' => 'title', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_REQUIRED), 'placeholder' => '填写任务名称'));
$oneform->addComp(array('name' => 'intro', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED), 'placeholder' => '详细介绍你的任务...'));
$oneform->addComp(array('name' => 'image', 'type' => Publish::$TP_PIC, 'label' => '上传图片(限5张)', 'imguploadLimit' => 5));
$oneform->addComp(array('name' => 'tag', 'type' => Publish::$TP_TAG, 'label' => '选择标签(点击标签可以移除)'));
$oneform->addComp(array('name' => 'amount', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_NUMBER), 'placeholder' => '输入报酬'));
$oneform->addComp(array('name' => 'is_crowdfunding', 'type' => Publish::$TP_COMBO, 'selectables' => array(1, 0),
    'selectlabels' => array('众筹', '非众筹'), 'label' => '是否众筹', 'isRequired' => true, 'defaultValue' => 0, 'errorMessage' => '请选择是否众筹', ));
$oneform->addComp(array('name' => 'task_type', 'type' => Publish::$TP_COMBO, 'selectables' => array('simple', 'tenders'),
    'selectlabels' => array('简单任务', '招标任务'), 'label' => '任务类型', 'isRequired' => true, 'defaultValue' => 'simple', 'errorMessage' => '请选择任务类型', ));
$oneform->addComp(array('name' => 'delivery_date', 'type' => Publish::$TP_DATE, 'validators' => array(Publish::$VAL_REQUIRED), 'label' => '交付日期', 'defaultValue' => ''));
?>
<div class="am-form-group am-tenders">
	<label for="task_target_num">招标的目标数量</label>
	<input type="number" name="max_targer_count" placeholder="招标的目标数量, 0表示不做限制" class="am-form-field ym-publish-field" value="0" id="task_target_num" />
</div>
<div class="am-form-group am-tenders">
	<label for="task_request_num">可以发起的申请数量</label>
	<input type="number" name="max_request_count" placeholder="可以发起的申请数量, 0表示不做限制" class="am-form-field ym-publish-field" value="0" id="task_request_num" />
</div>
<div class="am-form-group am-tenders">
	<label for="task_requirement">对参与者的需求描述</label>
	<textarea name="requirement" placeholder="对参与者的需求描述" class="am-form-field ym-publish-field" id="task_requirement" >

	</textarea>
</div>
<div class="am-form-group am-tenders">
	<label for="task_assign_solution">分配方案</label>
	<select name="assign_solution" class="am-form-field ym-publish-field" id="task_assign_solution" errormessage="请选择任务类型" validate="required">
		<option value="0" selected="">默认方案</option>
	</select>
</div>
<?php
$oneform->end();
?>
@section('scriptrange')
	$('.am-tenders').css('display','none');
	$('select[name="task_type"]').on('change',function(){
		var $this = $(this);
		var type = $this.val();
		if(type == 'tenders'){
			$('.am-tenders').css('display','');
		}else{
			$('.am-tenders').css('display','none');
		}

	});
@stop
@stop