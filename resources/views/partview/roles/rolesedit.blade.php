@extends('layouts.publish')
@section('title',  '编辑角色')
@section('formrange')
<link rel="stylesheet" href="/css/cropper.min.css" />
<link rel="stylesheet" href="/css/ym_publish.css" />

<?php
$oneform = Publish::form('/roles/edit', '编辑角色');
$oneform->addComp(array(
    'name' => 'name',
    'type' => Publish::$TP_TEXT,
    'validators' => array(Publish::$VAL_REQUIRED,
        Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 10)), ),
    'label' => '角色名称',
    'defaultValue' => $model->name,
    'placeholder' => '请填写您要发布的角色名称...',
    'errorMessage' => '请填写10个字符以内的角色名称', ));
?>
<div id="headerContainer" class="ym-imgfield" style="margin-bottom:1rem"></div>
<?php
$oneform->addComp(array(
    'name' => 'intro',
    'type' => Publish::$TP_TEXTAREA,
    'label' => '角色介绍',
    'placeholder' => '请填写角色介绍...',
    'defaultValue' => $model->intro,
    'errorMessage' => '请填写角色介绍', ));
$oneform->addComp(array(
    'name' => 'ip_id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $model->ip_id, ));
$oneform->addComp(array(
    'name' => 'id',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => $model->id, ));
$oneform->addComp(array(
    'name' => 'image',
    'type' => Publish::$TP_HIDDEN,
    'defaultValue' => '', ));

$oneform->end();
?>

@include('partview.publish.imagecontrols')
@stop
@section('scriptref')
    <script src="/js/cropper.min.js"></script>
	<script src="/js/ym_imageupload2.js"></script>
@stop
@section('scriptrange')
//<script >
//调用图片选择器
$('#headerContainer').ymImgField({
  'fieldName':'header',
  'maxCount':1,
  'aspectRatio':1/1,
  'previewFormat':'128w_128h_1e_1c',
  'btnText':'添加角色形象',
  'prefix':'http://img.umeiii.com/',
  'value':["{{$model->image->originName}}"],
  'descLabel':'请为角色选定设定头像区域',
  'gifOnly':false,
  'onValueChange': function(type, field, fileName){
	var fileOriginName =fileName;
	if( fileName.indexOf('@')>0){
		fileOriginName = fileName.substr(0,fileName.indexOf('@'));
	}
        var valueStr = $('input[name="image"]').val();
        if(type == 'add') {
            valueStr += (fileOriginName+';');
        }else {
            valueStr = valueStr.replace((fileOriginName+';'), '');
        }
        $('input[name="image"]').val(valueStr);
   }
  });
@stop
