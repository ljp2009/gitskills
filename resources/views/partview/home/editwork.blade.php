@extends('layouts.publish')
@section('title',  '发布我的作品')
@section('formrange')
<?php 
    $oneform = Publish::form('/home/edit', '编辑作品');
    $oneform->addComp(array(
            'name' => 'title',
            'type' => Publish::$TP_TEXT,
            'validators' => array(Publish::$VAL_REQUIRED,
                Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 7)),
            ),
            'label' => '作品名称',
            'placeholder' => '请填写您要发布的作品名称...',
            'errorMessage' => '请填写您要发布的作品名称',
            'defaultValue' => $userProduct->name, )
    );
    $oneform->addComp(array(
            'name' => 'isoriginal',
            'type' => Publish::$TP_RADIO,
            'selectables' => array(1, 2),
            'selectlabels' => array('原创', '非原创'),
            'label' => '是否原创', 'isRequired' => true,
            'defaultValue' => (is_null($userProduct->is_original)) ? '2' : $userProduct->is_original,
            'errorMessage' => '请选择是否原创', ));
    $oneform->addComp(array(
            'name' => 'issell',
            'type' => Publish::$TP_RADIO,
            'selectables' => array(1, 2),
            'selectlabels' => array('售卖', '不售卖'),
            'label' => '是否售卖',
            'isRequired' => true,
            'defaultValue' => (is_null($userProduct->is_sell)) ? '2' : $userProduct->is_sell,
            'errorMessage' => '请选择是否售卖', ));
//     $oneform->addComp(array('name'=>'image',
//     		'type'=>Publish::$TP_PIC,
//     		'label'=>'作品图片',
//     		'defaultValue'=>$userProduct->image,
//     		'imguploadLimit'=>1));
 ?>
 @include('partview.publish.imagetext',array('imageLimit'=>6,'imageNum'=>count($userProduct->image),'nameSeed'=>Utils::createRandomId('work'),
 			'imagetext'=>is_null(json_decode($userProduct->intro))?$userProduct->intro:json_decode($userProduct->intro,true)))
 <?php

    $oneform->addComp(array('name' => 'attrcode',
            'type' => Publish::$TP_COMBO,
            'isRequired' => true,
            'selectables' => $attrCode,
            'selectlabels' => $attrArr,
            'label' => '请选择作品属性',
            'isRequired' => true,
            'defaultValue' => (is_null($userProduct->attr_code)) ? '' : $userProduct->attr_code,
            'errorMessage' => '请选择作品属性', ));

    $oneform->addComp(array('name' => 'intro',
            'type' => Publish::$TP_TEXTAREA,
            'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '作品介绍',
            'defaultValue' => $userProduct->intro,
            'placeholder' => '请填写您的作品介绍...',
            'errorMessage' => '请填写您的作品介绍', ));

    $oneform->addComp(array('name' => 'sellintro',
            'type' => Publish::$TP_TEXTAREA,
            'label' => '售卖说明',
            'placeholder' => '请填写您的售卖说明...',
            'defaultValue' => (is_null($userProduct->sell_intro)) ? '' : $userProduct->sell_intro,
            'errorMessage' => '请填写售卖说明', ));
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $id));

    $oneform->end('修改');
    $aliOss = $oneform->getAliOssForm();
    echo $aliOss;
?>
<!-- <div class="ym_img_upload_control"> -->
    <!-- 图片	 -->
<!-- </div> -->

@section('scriptrange')
	var imgcount = $('input[name="image"]').attr('imgcount')*1;
	var maxcount = $('input[name="image"]').attr('maxcount')*1;
	if(imgcount == maxcount){
		$('#image_addbtn').css('display','none');
	}
	var sell = $('input[name="issell"]').val()*1;
	if(sell == 1){
		$('input[name="price"]').addClass('ym-publish-field');
		$('input[name="price"]').parent().css('display','');
	}else{
		$('input[name="price"]').removeClass('ym-publish-field');
		$('input[name="price"]').parent().css('display','none');
	}
	
	$('input[name="issell"]').on('change',function(){
		var issell = $(this).val()*1;
		if(issell==1){
			$('input[name="price"]').addClass('ym-publish-field');
			$('input[name="price"]').parent().css('display','');
		}else{
			$('input[name="price"]').removeClass('ym-publish-field');
			$('input[name="price"]').parent().css('display','none');
		}
	});
	$YN_VALIDATOR.validators['predeal']=
		    [function(v){
		    	var dom='',images = '';
				$('#image_text_area>div').each(function(i){
				  var classBox = $(this).attr('class');
				  if(classBox.indexOf('text-box') > -1){
				    var text = $.trim($(this).text());
				    if(text == '这里输入文本' || text == ''){
				      $(this).remove();
				    }else{
				      dom +='text-box_'+text+';}';
				    }
				  }else if(classBox.indexOf('img-box') > -1){
				    dom += 'img-box_'+$(this).find('img').attr('data-src');
				    dom += '_'+$.trim($(this).find('.img-desc').text())+';';
				    images += $(this).find('img').attr('data-src')+';}';
				  }else if(classBox.indexOf('link-box') > -1){
				    var linkName = $.trim($(this).find('a').text());
				    var linkUrl = $.trim($(this).find('a').attr('data-href'));
				    dom += 'link-box_'+linkName+'_'+linkUrl+';}';
				  }
				});
				console.log(dom);
				//var lastHtml = $.trim($('#image_text_area').html());
				//var image = $('#image_text_value').val();
				$('input[name="image_text_intro"]').val(dom);
				$('input[name="images_value"]').val(images);
				if(dom){
					return true;
				}else{
					return true;
				}
		    },'请添加图文混排内容'];
		$('input[name="image_text_intro"]').attr('validate','predeal'); 
@stop
@stop