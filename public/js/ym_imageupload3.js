jQuery.ymImgField = {};
jQuery.ymImgField.bindField = {};
jQuery.ymImgField.activeField = '';
jQuery.ymImgField.function = '';
jQuery.ymImgField.uploadToAli = function(){
    var aliForm = $('#ym_upload_form');
    var filectrl = $('#ym_upload_image_file');
    var $nameSeed = $('#ym_upload_name_seed');
    var token = $('#ym_upload_token').val();
    var seedCount = parseInt($nameSeed.attr('ct'))+1;
    var fileName = $nameSeed.val()+seedCount;
    var field = $.ymImgField.getActiveField();
    var realFileName = filectrl[0].files[0].name;
    var ext = realFileName.substr(realFileName.lastIndexOf('.') + 1).toLowerCase();
    if(field.gifOnly && ext != 'gif'){
       $('#ym-editimg-div').html('请上传一张gif图片。');
       return ;
    }
    fileName = fileName + '.' + ext;
    var redirectUrl = window.location.protocol + "//" + window.location.host
                    + "/img/img-callback/" + field.fieldName + "/" + fileName + "/"+token;
    //需要加入图片大小限制和图片的格式限制
    //var maxSize = valuectrl.attr("maxSize");
    //if(this.files[0].size > maxSize)
    //{
    //	alert("图片大小不能超过10MB");
    //	return;
    //}
    aliForm.find("input[name=key]").val(fileName);
    aliForm.find("input[name=content-Type]").val($.ymImgField.getImgContentType(ext));
    aliForm.find("input[name='success_action_redirect']").val(redirectUrl);
    aliForm.submit();
};
jQuery.ymImgField.selectedImage = function(fieldName, fileName){
    var st = $.ymImgField.bindField[fieldName];
    $('#ym-desc-label').hide();
    //设置了图片的裁切比例，生成图片编辑器
    if(typeof(st.aspectRatio) != 'undefined'){
      //图片原图预览
      $('#ym-editimg-div').html('<img id="ym-editimg" fileName="'+fileName
                              +'" cropper="0" fieldName="'+st.fieldName
                              +'" src="http://img.umeiii.com/'+fileName+'">');
      if(typeof(st.descLabel) != 'undefined'){
        $('#ym-desc-label').html(st.descLabel);
        $('#ym-desc-label').show();
        $('#ym-editimg-div').css('height','265px');
      }
       $('#ym-editimg').attr('cropper', 1);
       var options = {
          strict: false,
          responsive: false,
          checkCrossOrigin: false,
          zoomable: true,
          aspectRatio:st.aspectRatio,
          touchDragZoom: true,
          mouseWheelZoom: true,
          cropBoxMovable: false,
          cropBoxResizable:false,
          viewMode:1,
          dragMode:'move',
          movable:true
      };
      $('#ym-editimg').cropper(options);
    }else{
    //图片缩略图预览
      $('#ym-editimg-div').html('<img id="ym-editimg" class="ym-editimg" fileName="'+fileName
                              +'" cropper="0" fieldName="'+st.fieldName
                              +'" src="http://img.umeiii.com/'+fileName+'@300h_1l.gif">');
    }
};
jQuery.ymImgField.getActiveField = function(){
  return $.ymImgField.bindField[$.ymImgField.activeField];
};
//生成预览控件
jQuery.ymImgField.makePerviewDiv = function(st){
    return $('<div class="ym-imgfield-perview ym_imageupload_div" id= "ym-imgfield-pv-'+st.fieldName+'"></div>');
};
//获取预览框
jQuery.ymImgField.getPerviewDiv = function(st){
  return $('#ym-imgfield-pv-'+st.fieldName+'');
};
  //生成预览元素
jQuery.ymImgField.makePerviewItem = function(value, st){
    var perviewItem = $('<div flag="img-'+st.fieldName+'-'+value+'" class="ym_imageupload_div"></div>');
    var imgName = value;
    //如果定义了预览元素的样式则，追加这个样式(仅ali图床有效）
    if(typeof(st.previewFormat) != 'undefined'){
      imgName = st.prefix+value+(value.indexOf('@')>0?'|':'@')+st.previewFormat;
    }
    var img = $('<img src="'+imgName+'" class="ym_update_pic" style="max-width:100%"/>');
    perviewItem.append(img);
    // var rmBtn = $('<span class="ym-imgfield-perview-rmbtn">删除</span>');
    // perviewItem.append(rmBtn);
    // rmBtn.on('click',function(){
    //   $.ymImgField.removeValue(value, st);
    // });
    // var chBtn = $('#ym-imgfield-perview-img');
    // chBtn.on('click', function(){
    //   //记录当前的操作的field
    //   $.ymImgField.activeField = st.fieldName;
    //   $.ymImgField.showImageSelector({
    //     fieldSt:st,
    //     onSelect:function(st,fileName,fileRealName){
    //       $.ymImgField.removeValue(value, st);
    //       $.ymImgField.addValue(fileName, st);
    //     });
    //   });
    img.on('click', function(){
      //记录当前的操作的field
      $.ymImgField.activeField = st.fieldName;
      $.ymImgField.showImageSelector({
        fieldSt:st,
        onSelect:function(st,fileName,fileRealName){
          $.ymImgField.removeValue(value, st);
          $.ymImgField.addValue(fileName, st);
        }
      });

    });
    return perviewItem;
};
jQuery.ymImgField.getPerviewItem = function(value, st){
  return $('div[flag="img-'+st.fieldName+'-'+value+'"]');
};


// <li id="avatar_addbtn" class="ym_update_pic"  >
//                             <i class="ymicon-img"></i>
//                             <span>上传头像</span>
//                     </li>

//生成添加按钮
jQuery.ymImgField.makeAddBtn = function(st){

  var btnStr = '<div  class="ym_update_pic ym_imageupload_div"  ><i class="ymicon-img"></i><span>'+st.btnText+'</span></div>';

  // var btnStr = '<span class="ym-imgfield-addbtn am-btn am-btn-warning am-btn-block" >'+st.btnText+'</span>';
  if(typeof(st.customBtn)!='undefined'){
    btnStr = st.customBtn;
  }
  var addBtn = $(btnStr);
  addBtn.attr('id', 'ym-imgfield-abtn-'+st.fieldName);
    addBtn.on('click', function(){
      //记录当前的操作的field
      $.ymImgField.activeField = st.fieldName;
      $.ymImgField.showImageSelector({
        fieldSt:st,
        onSelect:function(st,fileName,fileRealName){
          $.ymImgField.addValue(fileName, st);
        }
      });
      if(st.value.length >= st.maxCount){
        addBtn.hide();
      }
    });
    return addBtn;
};
//获取添加按钮
jQuery.ymImgField.getAddBtn = function(st){
  return $('#ym-imgfield-abtn-'+st.fieldName+'');
};
//生成存值字段
jQuery.ymImgField.makeValueInput = function(st){
    return $('<input type="hidden" name="'+st.fieldName+'" value="" id="ym-imgfield-vf-'+st.fieldName+'"/>');
};
//获取存值字段
jQuery.ymImgField.getValueInput = function(st){
  return $('#ym-imgfield-vf-'+st.fieldName+'');
};
jQuery.ymImgField.showImageSelector = function(params){
  //获取历史图片
  var url = params.fieldSt.gifOnly?'/img/gif-history':'/img/history';
  $.get(url, function(data){
    var container = $('#ym-img-history');
    container.html('');
    var fieldName = $.ymImgField.activeField;
    if(data.length > 0){
      for(var i=0;i<data.length;i++){
        var histitem = $('<div class="ym-img-history-item"></div>');
        histitem.append('<img class="ym-img-history-img" imgName="'+data[i].name+'" src="'+data[i].url+'@64w_64h_1e_1c" />');
        histitem.on('click',function(){
           var imgName = $(this).find('img.ym-img-history-img').attr('imgName');
           $.ymImgField.selectedImage(fieldName,imgName);
        });
        container.append(histitem);
      }
    }else{
      var noImgStr = params.fieldSt.gifOnly?'没有近期上传的GIF图片。':'您近期没有上传过图片。';
      container.html('<span>'+noImgStr+'</span>');
    }
    var histitem = $('<div class="ym-img-history-additem"></div>');
    histitem.append('<img class="ym-img-history-addimg" src="/imgs/addimgbtn.png" />');
    histitem.on('click',function(){
      $('#ym_upload_image_file').click();
    });
    container.append(histitem);
    $('#ym-editimg-div').html();
    $('#ym-select-img').modal({relatedTarget:params,onConfirm:function(){
      var $img = $('#ym-editimg');
      var fileRealName = $img.attr('fileName');
      var fileName = fileRealName;
      if($img.attr('cropper') == 1){
        var cropBox = $img.cropper('getData');
        var cutLeft = parseInt(cropBox.x);
        var cutTop = parseInt(cropBox.y);
        var cutWidth = parseInt(cropBox.width);
        var cutHeight = parseInt(cropBox.height);
        var cutStr = cutLeft + '-' + cutTop + '-' + cutWidth + '-' + cutHeight + 'a';
        fileName = fileName + '@' + cutStr;
      }
      this.relatedTarget.onSelect(this.relatedTarget.fieldSt, fileName, fileRealName);
    }});
  });
};
jQuery.ymImgField.getImgContentType = function(ext){
    var data = { "bmp": "image/bmp", "gif": "image/gif", "jpe": "image/jpeg",
      "jpeg": "image/jpeg", "jpg": "image/jpeg", "png": "image/png" };
    return data[ext];
};
jQuery.ymImgField.addValue = function(value, st){
    var $valueInput = $.ymImgField.getValueInput(st);
    var $perviewDiv = $.ymImgField.getPerviewDiv(st);
    var $perviewItem = $.ymImgField.makePerviewItem(value, st);
    var $addBtn = $.ymImgField.getAddBtn(st);
    if(typeof(st.btnInPreview) != 'undefined' && st.btnInPreview){
      $addBtn.before($perviewItem);
    }else{
      $perviewDiv.append($perviewItem);
    }
    var valueStr = $valueInput.val();
    valueStr = valueStr+value+';';
    $valueInput.val(valueStr);
    var values = valueStr.split(';');
    if(values.length > 0 && (values.length-1) >= st.maxCount){
      $.ymImgField.getAddBtn(st).hide();
    }
    if(typeof(st.onValueChange) == 'function'){
      st.onValueChange('add', st, value);
    }
};
jQuery.ymImgField.removeValue = function(value, st){
    var $valueInput = $.ymImgField.getValueInput(st);
    var $perviewItem = $.ymImgField.getPerviewItem(value, st);
    $perviewItem.remove();
    var valueStr = $valueInput.val();
    valueStr = valueStr.replace(value+';', '');
    $valueInput.val(valueStr);
    // var values = valueStr.split(';');
    // if((values.length-1) < st.maxCount){
    //   $.ymImgField.getAddBtn(st).show();
    // }
    // if(typeof(st.onValueChange) == 'function'){
    //   st.onValueChange('remove', st, value);
    // }
};
/* params: 参数说明
 *'fieldName':图片绑定的字段,
 *'maxCount':最大上传数量,
 *'aspectRatio':图片编辑格式，上传或者选择的图片实际需要使用的部分的高宽比，不设置这项则使用原图，使用此项后显示编辑区域,例如1/1
 *'previewFormat':选中图片预览的格式，需要符合alioss的图片处理字符串格式'128w_128h_1e_1c',
 *'btnText':添加按钮显示的文字,
 *'prefix':图片路径，默认使用'http://img.umeiii.com/',
 *'value':[],控件初始化时候选中的值，数组，每一项为一个图片名
 *'descLabel':选择图片时，显示在编辑框上的说明文字，仅当设置了aspectratio后生效
 *'gifOnly':true,仅接受gif图片，默认为false
 *'onValueChange', 事件 当选择的图片值发生变化后触发此事件,事件结构为function(type, st, filename)
 *                type 接收 add / remove 两个值，add表示添加值，remove表示移除
 *                st 绑定字段的设定
 *                filename 添加或者删除的值
* */
(function($){$.fn.ymImgField = function(params){
  var settings = params;
  $.ymImgField.bindField[settings.fieldName] = settings;
  function getBindFunc(st){
    return function(){
      var $this = $(this);
      var perviewDiv =$.ymImgField.makePerviewDiv(st);
      var addbtn = $.ymImgField.makeAddBtn(st);
      var valueHid = $.ymImgField.makeValueInput(st);
      $this.append(perviewDiv);
      if(typeof(st.btnInPreview)!='undefined' && st.btnInPreview){
        perviewDiv.append(addbtn);
      }else{
        $this.append(addbtn);
      }
      $this.append(valueHid);
      for(var i=0; i<st.value.length; i++){
        $.ymImgField.addValue(st.value[i], st);
      }
    }
  }
  //绑定字段
  this.each(getBindFunc(settings));
}; })(jQuery);
