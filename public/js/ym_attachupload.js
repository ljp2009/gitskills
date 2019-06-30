/**
 * 上传附件
 * @author xiaocui
 * @date 2016-1-13
 */

var _ym_useAttachName = false;
var fileLimit = ['zip','mp3'];
var imageLimit = ['jpg','jpeg','png','gif','bmp'];
function addAttach(name) {
    var filectrl = $("#_uploadAttachFile");
    $("#attactFileName").val(name);
    filectrl.click();
}

function attachChange(filectrl, flag) {
	
    var name = $("#attactFileName").val();
    var imgctrl = $('#image_value');
    var valuectrl = $("#" + name + "_value");
    var maxCount = parseInt(valuectrl.attr("maxCount"));
    var attachCount = parseInt(valuectrl.attr("attachCount"));
    var imgMaxCount = parseInt(imgctrl.attr("maxCount"));
    var imgCount = parseInt(imgctrl.attr("imgCount"));
    
    var fileName = filectrl.files[0].name;
    var ext = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
    if($.inArray(ext,fileLimit) < 0 && $.inArray(ext,imageLimit) < 0){
    	alert("附件必须为zip格式或者MP3格式的音乐或者图片");
    	return;
    }
    var maxSize = valuectrl.attr("maxSize")*1024*1024;
    var nameSeed = valuectrl.attr("nameSeed");
    var nameIndex = valuectrl.attr("nameIndex");
    var imageName = nameSeed + nameIndex + "." + ext;
    if($.inArray(ext,fileLimit) >= 0){
    	if (attachCount >= maxCount) {
            alert("每次只能上传一个附件，请打包好再上传");
            return;
        }
    	if(filectrl.files[0].size > maxSize)
        {
        	alert("附件大小不能超过"+valuectrl.attr("maxSize")+"MB");
        	return;
        }
    	valuectrl.attr("nameIndex", parseInt(nameIndex) + 1);
    }else if($.inArray(ext,imageLimit) >= 0){
    	if (imgCount >= imgMaxCount) {
            alert("上传图片数量超过限制。");
            return;
        }
    	var nameSeed = imgctrl.attr("nameSeed");
        var nameIndex = imgctrl.attr("nameIndex");
    	name = 'image';
    	imgctrl.attr("nameIndex", parseInt(nameIndex) + 1);
    	imageName = nameSeed + nameIndex + "." + ext;
    }
    
    
    if (_ym_useAttachName) imageName = nameSeed + fileName;
    var formctrl = $("#_uploadAttachForm");
    formctrl.find("input[name=key]").val(imageName);
    formctrl.find("input[name=content-Type]").val(getContentType(ext));
    var redirectUrl = window.location.protocol + "//" + window.location.host + "/t2.php?name=" + name + "&imgname=" + imageName + '&originName=' + filectrl.files[0].name;
    formctrl.find("input[name='success_action_redirect']").val(redirectUrl);
    
    formctrl.submit();
}

function uploadAttachCallBack(name, imageName,fileName) {
    var valuectrl = $("#" + name + "_value");
    var listctrl = $("#" + name + "_thumbnaillist");
    var maxCount = parseInt(valuectrl.attr("maxCount"));
    var imgctrl = $('#image_value');
    var imgMaxCount = parseInt(imgctrl.attr("maxCount"));
    //var btnctrl = $("#"+name+"_addbtn");
    if(name == 'image'){
        var imgCount = parseInt(imgctrl.attr("imgCount"));
        imgctrl.attr('imgCount', parseInt(imgCount) + 1);
        var v = imgctrl.val();
        imgctrl.val(v + imageName + ";");
    }else{
    	var imgCount = valuectrl.attr('attachCount');
        valuectrl.attr('attachCount', parseInt(imgCount) + 1);
        valuectrl.val(imageName);
    }
    if(parseInt(imgctrl.attr('imgCount')) == imgMaxCount && parseInt(valuectrl.attr('attachCount')) == maxCount){
    	 $("#" + name + "_addbtn").hide();
    }
    var str = generateAttachThumbnail(name, imageName,fileName);
    $('#attachment_thumbnaillist').prepend(str);
}

function generateAttachThumbnail(name, imageName,fileName) {
	var str = '';
	if(name == 'image'){
		str = '<li style="padding-bottom:0">' +
	    ' <img id="' + name + '_' + imageName + '" imgName="' + imageName + '" class="am-thumbnail ym-flag-' + name + '" ' +
	    ' style="margin-bottom:5px" src="' + getImgBaseUrl(imageName) + '@64h_64w_1e_1c"' +
	    ' onclick="deleteAttach(this,\''+name+'\')" />' +
	    '</li>';
	}else{
		str = '<li style="padding-bottom:0;border:none;width:auto !important;">' +
	    '<label id="' + name + '" imgName="' + imageName + '" class="am-thumbnail ym-flag-' + name + '" ' + ' onclick="deleteAttach(this,,\''+name+'\')" ' +
	    'style="margin-bottom:5px;font-size:12px;background-color: #5eb95e;color:#fff;"' + '">' + fileName
	    '</label></li>';
	}
	return str;
}

function showAttachDetail(name, imageName) {
    $("#showFileName").attr('src', getImgBaseUrl(imageName) + "@400w_1e_1c");
    $("#showFileName").attr('ctrlName', name + "_" + imageName);
    $("#showFileName").parent().parent().parent().css('maxHeight','420px');
    $("#showFileName").parent().css('height','100%');
    $('#your-modal').modal({});
    $("#showFileName").parent().parent().parent().css({'marginTop':'-210px','overflowY':'auto'});
}

function deleteAttach(o,name) {
	$('#my-confirm-attach-content').html('你确定要删除这个文件吗？');
	var valuectrl = $("#" + name + "_value");
    var imgctrl = $('#image_value');
    $('#my-confirm-attach').modal({'onConfirm':function(c){
    	$(o).parent().remove();
    	if(name == 'image'){
    		var tmpV = imgctrl.val();
    		var ctrlName = $(o).attr('imgname');
    	    tmpV = tmpV.replace(ctrlName + ";", '');
    	    imgctrl.val(tmpV);
    	    imgctrl.attr('imgCount', parseInt(imgctrl.attr('imgCount') - 1));
    	}else{
    		$('input[name="attachment"]').val('');
            valuectrl.attr('attachCount', parseInt(valuectrl.attr('attachCount') - 1));
    	}
        $('#attachment_addbtn').css('display','block');
        $('#your-modal').modal('close');
    }});
}


