@extends('layouts.publish')
@section('title', '认证申请')
@section('formrange')
<?php


    $oneform = Publish::form('/certification/apply', '发起认证申请');

    $oneform->addComp(array(
        'name' => 'skillName',
        'type' => Publish::$TP_COMBO,
        'selectitems' => $skills,
        'label' => '技能',
        'validators' => array(Publish::$VAL_REQUIRED),
    ));
    $oneform->addComp(array(
        'name' => 'skillLevel',
        'type' => Publish::$TP_COMBO,
        'selectitems' => $skillsLevels,
        'label' => '技能等级',
        'validators' => array(Publish::$VAL_REQUIRED),
    ));

    $oneform->addComp(array(
        'name' => 'instruction',
        'type' => Publish::$TP_TEXTAREA,
        'label' => '申请说明',
        'validators' => array(Publish::$VAL_REQUIRED),
        'placeholder' => '请填写申请说明',
        'errorMessage' => '请填写申请说明',
    ));

    $oneform->addComp(array(
        'name' => 'isAjaxPost',
        'type' => Publish::$TP_HIDDEN,
        'defaultValue' => 1,
    ));
?>
	<div class="am-form-group" style="margin-bottom:5px">
		<input type="hidden" id="procard-add-max-num" value="{{$addProMaxNum}}">
	    <div id="procard-container" style="position:relative"> </div>
	</div>
	 <input type="hidden" name="mySkill" id="mySkill" value="{{$mySkill}}">
     <input type="hidden" name="mySkillLevel" id="mySkillLevel" value="{{$mySkillLevel}}">

<?php

    $oneform->end('发起申请', 'navbar');
?>
	<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
	  <div class="am-modal-dialog">
	    <div class="am-modal-hd">选择我的作品</div>
	    <div class="am-modal-bd">
	    <input id="keywd" type="text" class="am-modal-prompt-input"
	    style="margin:0 0 5px 0;width:100%" placeholder="请输入作品名称" onchange="searchPro()"/>
	    <div id="prolist" class="ym-select-prolist">
	    </div>
	    <button class="am-btn am-btn-primary am-btn-block" id="btn_pro_select" style="margin-top:10px"
	    			onclick="makesureSelectPro()">确认</button>
	    </div>
	  </div>
	</div>

	<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
	  <div class="am-modal-dialog">
	    <div class="am-modal-bd">
	      认证申请发起成功！
	    </div>
	    <div class="am-modal-footer">
	      <span class="am-modal-btn">确定</span>
	    </div>
	  </div>
	</div>
@stop
	
@section('scriptrange')

//<script>

function loadEditInit(){
	//默认技能不为空
	if ($('#mySkill') != '') {
		var skill = $('#mySkill').val();
		$("select[name='skillName']  option[value ='"+skill+"']").attr('selected', true);
	};
	if ($('#mySkillLevel') != '') {
		var skillLevel = $('#mySkillLevel').val();
		$("select[name='skillLevel']  option[value ='"+skillLevel+"']").attr('selected', true);
	};
	
}
loadEditInit();
//加载作品
function loadProCard(proInfo, type){
	var procard = makeProduction('procard',proInfo, type);
	var container = $('#procard-container');
	// container.html('');
	container.append(procard);
	return container;
}

//拼接作品显示
function makeProduction(name, proInfo, type){
	var mClass;
	var mClassSel;
	//添加作品按钮
	if (type == 0) {
		mClass = "ym-certification-procard1";
	//作品
	} else {
		mClass = "ym-certification-procard";
	};
	//已经选择的作品
	if(type == 1){
		mClassSel = "ym-certification-procard-info";
	} else {
		mClassSel = "ym-certification-procard-info1";
	}
	var mainDiv = $('<div id="'+name+'_'+proInfo.id+'" class="'+mClass+'" onclick="addPro(this)" ><div>');
    mainDiv.append('<img src="'+proInfo.cover+'@58h_58w_1e_1c" class="ym-certification-procard-img" />'
    				+'<input type="checkbox" style="display:none" id="cb_pro'
    				+proInfo.id+'"  name="pro_id[]" value="'+proInfo.id+'"/>');
    var infoDiv =$('<div class="'+mClassSel+'"></div>');
    infoDiv.append('<span class="ym-certification-procard-info-title">'+proInfo.name+'</span>');
    infoDiv.append('<span class="ym-certification-procard-info-text">'+proInfo.intro+'</span>');
    mainDiv.append(infoDiv);

    if (type == 1) {
    	mainDiv.append('<div class="ym-certification-procard-flag" onclick="deletePro(this);event.stopPropagation();">删除</div>');
    };
    
    return mainDiv;
}
//弹出选择作品对话框
function addPro(obj){

	//不是添加按钮
	if ('procard_add' != obj.id) return;
	$('#prolist').html('');
	$('#keywd').val("");
	$('#my-prompt').modal('open');
}
//清空所选的作品
function addProBtn(){
    loadProCard({ 'id':'add', 'cover':'http://img.umeiii.com/default.jpg', 'name':'点击选择我的作品',
             'intro':'最多添加三个作品' }, 0);
}
//检索作品
function searchPro(){
	var kw = $('#keywd').val();
	if (kw == '') return;
	$.post('/certification/quicksearch',{
		'_token':$('meta[name="csrf-token"]').attr('content'), 'keywd':kw},
		function(pros){
			var container = $('#prolist');
            container.html('');
            for (var i = 0; i < pros.length; i++) {
            	var proItem = $('<div class="ym-select-proitem"></div>');
                proItem.append(makeProduction('prolistItem',pros[i], 2));
                proItem.append('<input name="checkbox" style="display:none" id="cb'+pros[i].id +'" type="checkbox" />');
                container.append(proItem);
               
                proItem.on('click',makeSelectProClick(pros[i]));
            }

	}).error(function(a){
         alert(a);
    });
}

//选择作品
function makeSelectProClick(proInfo){
	return function(){
		//已经添加的作品数量
	    var nowProSel = $("#procard-container .ym-certification-procard").length;
	    //添加作品数量的最大值
	    var addMaxNum = $("#procard-add-max-num").val();
		//选择单元id
		var prolistItem_id = "#prolistItem_"+proInfo.id;
		//记录选中单元
		var cb_id = "#cb"+proInfo.id;
		
		//已经选中
		if ($(cb_id).is(':checked')) {
			$(cb_id).prop("checked", false);
			$(prolistItem_id).removeClass("ym-select-proitem-select");
		//未被选中
		} else {
			var $YN_VALIDATOR = new ym_validator();
			//获取已选择作品数量
			var selectitemsNum = $("#prolist .ym-select-proitem-select").length;

			//已经达到选择作品的最大数量
			if (selectitemsNum >= addMaxNum - nowProSel){
				$YN_VALIDATOR.handleErrorMessage("最多只能选择"+(addMaxNum - nowProSel)+"个作品");	
				return;
			}
			$(cb_id).prop("checked", true);
			$(prolistItem_id).addClass("ym-select-proitem-select");
		};
	}
}
//确认选择作品
function makesureSelectPro(){
	//删除添加作品按钮
	$('#procard_add').remove();
	var proInfo = new Object();
	$("#prolist .ym-select-proitem-select").each(function(){

		proInfo.cover =$(this).find("img").attr("src").split('@')[0];
		proInfo.id = $(this).find("input").attr("value");
		proInfo.name = $(this).find(".ym-certification-procard-info-title").text();
		proInfo.intro = $(this).find(".ym-certification-procard-info-text").text();

		//显示选择的作品
    	loadProCard(proInfo, 1);
    	//保存选中的作品id
    	var pro_id = "#cb_pro"+proInfo.id;
		$(pro_id).prop("checked", true);
	});


	
    //已经添加的作品数量
    var nowProSel = $("#procard-container .ym-certification-procard").length;
    //添加作品数量的最大值
    var addMaxNum = $("#procard-add-max-num").val();

    //作品数量没有达到最大值时，在最后添加作品按钮
    if (nowProSel< addMaxNum) {
    	//显示添加作品按钮
    	addProBtn();
    }
    $('#my-prompt').modal('close');
}

//删除作品
function deletePro(obj){
	
	//已经添加的作品数量
    var nowProSel = $("#procard-container .ym-certification-procard").length;
    //添加作品数量的最大值
    var addMaxNum = $("#procard-add-max-num").val();
    //删除作品
	$(obj).parent().remove();
	//已经添加的作品数量达到最大值
	if (nowProSel == addMaxNum) {
		//显示添加作品按钮
    	addProBtn();
	};
}

<!-- 提交申请结果显示框 -->
$('.am-modal-footer').click(function(){
 	//返回认证申请列表申请
    location.href = '/certification/list/0';
});

addProBtn();
@stop