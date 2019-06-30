@extends('layouts.publish')
@section('title',  '百度百科解析器')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<?php 
    $oneform = Publish::form('/common/baiduimport/search', '百度百科解析器');
    $oneform->addComp(array('name' => 'search', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '输入词条', 'placeholder' => '请输入词条...', ));
    $oneform->addComp(array('name' => 'option', 'type' => Publish::$TP_COMBO, 'label' => '选项',
        'selectables' => array('0', '1', '2'), 'selectlabels' => array('存在则什么都不作', '存在则从本地文件加载', '存在则重来'),
        'defaultValue' => '0', ));
    $oneform->end();
?>
</div>
@stop