@extends('layouts.publish')
@section('title',  '发布我的作品')
@section('formrange')
<?php
    $oneform = Publish::form('/home/create', '发布作品');
    $oneform->addComp(array('name' => 'title', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_REQUIRED,
        Publish::createOneValidator(Publish::$VAL_LIMIT, array(1, 20)),
    ),
        'label' => '作品名称', 'placeholder' => '请填写您要发布的作品名称...', 'errorMessage' => '请填写20个字以内的作品名称', ));
?>
@include('partview.publish.imagetext',array('imageLimit'=>6,'imageNum'=>0,'nameSeed'=>Utils::createRandomId('work')))

<?php
    $oneform->addComp(array('name' => 'id', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => 0));
    $oneform->end();
?>
@section('scriptref')
    <script src="/js/cropper.min.js"></script>
	<script src="/js/ym_imageupload2.js"></script>
@stop
@section('scriptrange')
//<script>
function selectImage(){
    var st = { 'fieldName':'image', 'gifOnly':false };
    $.ymImgField.bindField[st.fieldName] = st;
    $.ymImgField.activeField = st.fieldName;
    $.ymImgField.showImageSelector({
        fieldSt:st,
        onSelect:function(st,fileName,fileRealName){
            imageCallBack(st.fieldName,fileName);
        }
      });
}
$YN_VALIDATOR.validators['predeal']=
		    [function(v){
		    	var dom='',images = '';
				$('#image_text_area>div').each(function(i){
				  var classBox = $(this).attr('class');
				  if(classBox.indexOf('text-box') > -1){
				    var text = $.trim($(this).find('.text-area').text());
				    if(text == '这里输入文本' || text == ''){
				      $(this).remove();
				    }else{
				      dom +='text-box_'+text+';}';
				    }
				  }else if(classBox.indexOf('img-box') > -1){
				    dom += 'img-box_'+$(this).find('img').attr('data-src');
				    dom += '_'+$.trim($(this).find('.img-desc').text())+';';
				    images += $(this).find('img').attr('data-src')+';';
				  }else if(classBox.indexOf('link-box') > -1){
				    var linkName = $.trim($(this).find('a').text());
				    var linkUrl = $.trim($(this).find('a').attr('data-href'));
				    dom += 'link-box_'+linkName+'_'+linkUrl+';}';
				  }
				});
				//var lastHtml = $.trim($('#image_text_area').html());
				//var image = $('#image_text_value').val();
				$('input[name="image_text_intro"]').val(dom);
				$('input[name="images_value"]').val(images);
				if(dom){
					return true;
				}else{
					return false;
				}
		    },'请添加图文混排内容'];
		$('input[name="image_text_intro"]').attr('validate','predeal'); 
    var interval = setInterval(function(){
        var myDate = new Date();
        $.post('/pub/create-work',{
            '_token' : $.ymFunc.getToken(),
            'time'   : myDate
        }, function(data){
            
        }).error(function(err){
            alert(myDate);
        });
    }, 60000);


@stop
@stop
