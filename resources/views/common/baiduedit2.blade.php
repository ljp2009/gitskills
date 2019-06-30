@extends('layouts.publish')
@section('title',  '百度百科解析器2')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<?php 
    function attachSearchButton($type)
    {
        echo '<button class="am-btn am-btn-primary" onclick="postSearch(\''.$type.'\')">';
        echo '<i class="am-icon-search-plus"></i>搜索';
        echo '</button>';
    }
    function appendPreviewImageControl($name, $pics, $label = '')
    {
        $IMGALIGN = 3;
        $id = Utils::createRandomId($name);
        echo '<div class="am-form-group">';
        echo '<input type="hidden" class="am-form-field" value="'.$pics.'" name="'.$name.'" id="'.$id.'" />
		';
        if (strlen($pics) > 0) {
            if (strlen($label) > 0) {
                echo '<label>'.$label.'</label>';
            }
            echo '<div class="am-g">
			';
            if (strpos($pics, ';') > 0) {
                $thepics = explode(';', $pics);
            } else {
                $thepics = array($pics);
            }
            $sz = sizeof($thepics);
            $left = $sz % $IMGALIGN;
            $vspace = 12 / $IMGALIGN;
            $ct = 0;
            foreach ($thepics as $pic) {
                ++$ct;
                $forname = '_forimg_'.$id;
                $forid = '_forimg_'.$id.'_'.$ct;
                echo '<div class="am-u-sm-'.$vspace.'" >
					<a href="javascript:deleteme(\''.$forid.'\', \''.$forname.'\', \''.$id.'\')"  >
					<img src="'.$pic.'" name="'.$forname.'" id="'.$forid.'" title="点击删除"  isdeleted="false" class="ym_img_upload_preview imgprev"></a>
					</div>';
            }
            if ($left > 0) {
                for ($i = 0; $i < $left; ++$i) {
                    echo '<div class="am-u-sm-'.$vspace.'" ></div>
					';
                }
            }
            echo '</div>';
        }
        echo '</div>';
    }

    $oneform = Publish::form('/common/baiduimport/import', '百度百科解析器2');
    $oneform->addComp(array('name' => 'functype', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $functype));
    $oneform->addComp(array('name' => 'zpm', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '作品名', 'placeholder' => '作品名...', 'defaultValue' => $zpm, ));
    appendPreviewImageControl('fmt', $fmt, '封面图片');
    // $oneform->addComp(array('name'=>'zz', 'type'=>Publish::$TP_TEXT, 'validators'=>array(Publish::$VAL_REQUIRED),
    //   		'label'=>'作者/监督/导演', 'placeholder'=>'作者/监督/导演...', 'defaultValue'=>$zz));

    $oneform->addComp(array('name' => 'jbxx', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '基本信息', 'placeholder' => '基本信息...', 'defaultValue' => $jbxx, ));
    attachSearchButton('author');
    $oneform->addComp(array('name' => 'nrms', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '内容描述', 'placeholder' => '内容描述...', 'defaultValue' => $nrms, ));
    $oneform->addComp(array('name' => 'zj', 'type' => Publish::$TP_TEXT, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '主角', 'placeholder' => '主角...', 'defaultValue' => $zj, ));
    attachSearchButton('hero');
    appendPreviewImageControl('zjt', $zjt, '主角图片');
    $oneform->addComp(array('name' => 'zjms', 'type' => Publish::$TP_TEXTAREA, 'validators' => array(Publish::$VAL_REQUIRED),
            'label' => '主角描述', 'placeholder' => '主角描述...', 'defaultValue' => $zjms, ));

    $oneform->end();
?>
</div>
<script type="text/javascript">
	document.getElementsByName('jbxx')[0].readOnly = "readonly";
	function resetImgValue(forname, inputid ){
		var value = "";
		$('[name=' + forname +']').each(function(index){
			if($(this).attr('isdeleted')=='true')
				value += $(this).attr('src') + ';';
		});
		$('#' + inputid).val(value);
	}
	function deleteme(forid, forname, inputid){
		var obj = $('#' + forid);
		if(obj.attr('isdeleted')=='false'){
			obj.attr('isdeleted', 'true');
			obj.addClass('ym_img_upload_disable');
		}else{
			obj.attr('isdeleted', 'false');
			obj.removeClass('ym_img_upload_disable');
		}
		resetImgValue(forname, inputid);
	}
	function postSearch(type){
		var form = $('form');
		form.attr('action', '/baidu/' + type);
		form.submit();
	}
</script>
@stop
