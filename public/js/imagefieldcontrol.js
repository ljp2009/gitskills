function getImgValue(){
    var imgFullValue = '';
    $('.imgValue:not(#addImg)')
        .each(function(){
            imgFullValue += ($(this).find('img').first().attr('imgname')+';');
        });
    $('input[name=image]').val(imgFullValue);
}
function addImgValue(name, url){
    var $addBtn = $('#addImg');
    var fileUrl= url;
    if(fileUrl.indexOf('@')>=0){
        fileUrl += ('|'+"64w_64h_1e_1c");
    }else{
        fileUrl += ('@'+"64w_64h_1e_1c");
    }
    var $pvDiv = $('<div class="imgValue"><img imgname="'+name+'" origin="'+url+'" src="'+fileUrl+'"></div>');
    $addBtn.before($pvDiv);
    $pvDiv.on('click', function(){
       var $img = $(this).find('img');
       previewImg($img.attr('imgname'), $img.attr('origin'));
    });
    checkAddBtn();
    getImgValue();
}
function checkAddBtn(){
    var $addBtn = $('#addImg');
    var $name = $('input[name="image"]');
    var maxlength = $name.attr('maxct');
    var $values = $('.imgValue');
    if(maxlength <= $values.length-1){
        $addBtn.hide();
    }else{
        $addBtn.show();
    }
}
function previewImg(imgname, url){
    var $shade = $('#detailImgShade');
    $shade.remove();
    $shade = $('<div id="#detailImgShade" class="ym_fp_shade"></div>');
    $('body').append($shade);
    var $view = $('<div class="ym_cm_imgpreview"></div>');
    $view.css({'background-image':"url("+url+")"})
    $shade.append($view);
    var $btndel = $('<button type="button" class="delete">删除</button>');
    $btndel.on('click',function(){
        $('img[imgname="'+imgname+'"]').parent().remove();
        checkAddBtn();
        getImgValue();
    });
    $shade.append($btndel);
    var $btncancel = $('<button type="button" class="cancel">关闭</button>');
    $shade.append($btncancel);
    $('body').css('overflow', 'hidden');
    $shade.on('click',function(){$shade.remove();});
    $shade.show();
    $view.show();
}



function bindImageField(fname, setting){
  //修正图片:true:弹出修正图片框，false:不弹出
  var useImgEditerEnable = false;
  if ($('input[name="imageedit"]').attr('value') == 1) {
      useImgEditerEnable = true;
  };
  $('#addImg').scUploadImageWork({
      name:'background',
      maxFileSize:5,
      allowAnimation:true,
      useImgEditer:useImgEditerEnable,
      uploadUrl:'/img/policy',
   })
   .bind('beforeUpload', function(uploadSet, params){
       uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/'+fname;
   }, null)
   .bind('afterUpload', function(imgInfo, res){
       if(res == null){
           alert('上传失败了。');
       }else{
          addImgValue(res.field+'/'+res.filename, res.url);
       }
   }, null);
}
function imgFieldCtrl(uopts){
  var settings = {
    "container"      : "",
    "maxCt"          : 9,
    "allowAnimation" : true,
    "fieldName"      : "image",
    "values"         : [],
  };
  var icons = [];
  var ifc = this;
  var objs = {};
  var funcs = {};
  var cls = {};

  cls.icon = function(fileName, url, onClick){
    var fname = fileName;
    var fileUrl= url;
    var iconObj = this;
    var furl = url;
    if(fileUrl.indexOf('@')>=0){
        fileUrl += ('|'+"64w_64h_1e_1c");
    }else{
        fileUrl += ('@'+"64w_64h_1e_1c");
    }
    var dom = $("<div class='imgValue'><img src='"+fileUrl+"' /></div>");
    dom.on('click', function(){
      onClick(iconObj);
    });
    this.getValue = function(){
      return fname;   
    };
    this.getDom = function(){
      return dom;
    };
    this.getUrl = function(){
      return furl;
    };
    this.remove = function(){
      dom.remove(); 
      funcs.removeValue(iconObj);
    };
  };

  cls.preview = function(){
    var shade = null;
    var view = null;
    var bindObj = null;
    var init = function (){
      if(shade == null){
        shade = $('<div class="ym_fp_shade"></div>');
        $('body').append(shade);
        view = $('<div class="ym_cm_imgpreview"></div>');
        shade.append(view);
        var btndel = $('<button type="button" class="delete">删除</button>');
        var btncancel = $('<button type="button" class="cancel">关闭</button>');
        shade.append(btndel);
        shade.append(btncancel);
        shade.on('click', function(){
          shade.hide();
        });
        btndel.on('click', function(){
          bindObj.getDom().remove(); 
          bindObj.remove();
        });
      }
    };
    this.show = function(obj){
      bindObj = obj;
      view.css({'background-image':"url("+obj.getUrl()+")"});
      shade.show();
      view.show();
    };
    init();
  };

  funcs.init = function(){
    for(var key in uopts){
      if(typeof(settings[key]) != 'undefined'){
        settings[key] = uopts[key];
      }
    }
    
    objs.container = $(settings.container);
    objs.container.html('');
    objs.value =$("<input type='hidden' name="+settings.fieldName+" />");
    objs.add =$("<div class='imgValue add_btn'><img src='/imgs/imgbtn.jpg' /></div>");
    objs.preview = new cls.preview();
    objs.container.append(objs.value);
    objs.container.append(objs.add);
    objs.add.scUploadImageWork({
        name:settings.fieldName,
        maxFileSize:settings.maxCt,
        allowAnimation:settings.allowAnimation,
        useImgEditer:false,
        uploadUrl:'/img/policy',
     })
     .bind('beforeUpload', function(uploadSet, params){
         uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/'+settings.fieldName;
     }, null)
     .bind('afterUpload', function(imgInfo, res){
         if(res == null){
             alert('上传失败了。');
         }else{
            funcs.addValue('/'+settings.fieldName+'/'+res.filename, res.url);
         }
     }, null);
  };
  funcs.addValue = function(fileName, url){
    var icon = new cls.icon(fileName, url, function(){objs.preview.show(icon);});
    icon.getDom().insertBefore(objs.add);
    icons.push(icon);
    if(icons.length >= settings.maxCt){
      objs.add.hide();
    }
    objs.value.val(objs.value.val()+fileName+';');
  };
  funcs.removeValue = function(icon){
    var index = -1;
    for(var i=0; i<icons.length; i++){
      if(icons[i] == icon){
        index = i;
        break; 
      }
    }
    if(index >= 0){
      objs.value.val(objs.value.val().replace(icon.getValue()+';', ''));
      icons.splice(index, 1);  
    }
    if(icons.length < settings.maxCt){
      objs.add.show();
    }
  };
  funcs.updateValueCtrl = function(){};
  this.setValue = function(values){
    for(var i=0; i<values.length; i++){
      funcs.addValue(values[i].fileName, values[i].url);
    }
  };
  this.refresh = function(){
    var vals = objs.value.val();
    for(var i=0; i<icons.length;i++){
      icons[i].getDom().remove();
    }
    icons = [];
    var valArr = vals.split(';');
    objs.value.val('');
    for(var i=0; i<valArr.length; i++){
      if(valArr[i].length > 0){
        funcs.addValue(valArr[i], 'http://img.umeiii.com/'+valArr[i]);
      }
    }
  };
  funcs.init();
}

