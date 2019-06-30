(function($){$.extend($.fn, { scUploadImageWork:function(uopts){
  //程序设置信息
  var options = {
    name:'scUploadImage',//控件名称
    allowAnimation:false, //是否允许GIF动画，如果不运行则只上传GIF第一帧并转化为默认格式，如果此项设置为true则不压缩gif，并检查图片大小
    quality:7,//图片质量（1~10）,10的时候质量最好
    maxImageSize:{w:1080,h:0}, //图片的极限大小，0表示不不限制,
    maxFileSize:4, //文件极限大小，单位mb
    typeFilter:['jpg', 'png', 'gif', 'bmp'],//图片类型
    imgNamePrefix:'def', //图片名字的前缀
    useImgEditer:true,//使用图片编辑器,默认使用cropper编辑器，也可以通过调用bindImageEditer来绑定自定的编辑器
    cropperSet:{},//默认的cropper设置，在useImgEditer启用的时候有效
    uploadDrive:'alioss',//文件上传器，默认上传到ali云
    uploadUrl:'/policy.php'//上传地址
  };
  //文件信息
  var _imageInfo = {
    //文件类型
    fileType:null,
    //原始文件属性
    originImage:{data:null, size:null, name:''},
    //预处理后文件的属性
    processImage:{data:null, size:null, name:''},
    //最终文件属性
    finalImage:{data:null, size:null, name:''},
  };
  //工具函数
  var toolFunc = { };
  // 图片处理函数
  var imgFunc = { };
  // 文件处理函数
  var fileFunc = { };

  //用户绑定的事件
  var customEvents = {
    catchError:null,
    callImageEditer:null,
    beforeUpload:null,
    afterUpload:null
  };
  //插件执行管道
  var execPipe = { };

  //初始化函数
  toolFunc.init = function(userOpts){
   if(typeof(userOpts) != 'object') return;
   for(var key in userOpts) {
     if(typeof(options[key]) != 'undefined'){
       options[key] = userOpts[key];
     }
   }
   if(options.quality>10) options.quality = 10;
   if(options.quality<1) options.quality = 1;
  };
  //生产文件选择控件
  toolFunc.generateFCtrl = function(){
      var ctrlId = options.name+'FileCtrl';
      $file =$('#'+ ctrlId); 
      if($file.length == 0);{
        var $file = $('<input type="file" id="'+ctrlId+'" style="display:none" />');
        $('body').append($file);
      }
      return $file;
  };
  //检查文件类型
  toolFunc.checkFileType = function(file){
    var arr = options.typeFilter;
    if(/^(image\/jpeg)$/i.test(file.type) && arr.indexOf('jpg')>=0){
      return true;
    }
    if(/^(image\/png)$/i.test(file.type) && arr.indexOf('png')>=0){
      return true;
    }
    if(/^(image\/gif)$/i.test(file.type) && arr.indexOf('gif')>=0){
      return true;
    }
    if(/^(image\/bmp)$/i.test(file.type) && arr.indexOf('bmp')>=0){
      return true;
    }
    return false;
  };
  //检查文件类型
  toolFunc.checkFileSize = function(file){
    if(options.maxFileSize>0){
      return options.maxFileSize*1024*1024 >= file.size;
    }
    return true;
  };

  //定义异常捕捉器
  toolFunc.catchError = function(type, error){
    console.log('[Error]'+type+':'+error);
    if(customEvents.catchError != null){ customEvents.catchError(type, error); }
  };

  toolFunc.cloneImage = function(image){
    var res = {};
    res.name = image.name;
    res.data = image.data;
    res.size = {};
    res.size.x = image.size.x; 
    res.size.y = image.size.y; 
    res.size.w = image.size.w; 
    res.size.h = image.size.h; 
    res.type = image.type;
    return res;
  }; 
  toolFunc.showLoading = function(){
    $('.sc_image_loading').remove();
    var $shade = $('<div class="sc_image_loading"><div><span>图片上传中</span></div></div>');
    $('body').append($shade);
    $shade.show();
  };
  toolFunc.hideLoading = function(){
    setTimeout(function(){
    $('.sc_image_loading').remove();
    }, 1000);
  };
  /* * * * * *
   * 处理管道
   * * * * * */
  //预处理上传图片
  execPipe.readFile = function(file){
    console.log('[Pipe]ReadFile');
    if(!imgFunc.checkFile(file)){
      return;
    };
    if(_imageInfo.fileExt == '.gif' && options.allowAnimation){
      imgFunc.readFile(execPipe.callImageEditer);
    }else{
      if(_imageInfo.fileExt == '.jpg'){
        imgFunc.readFile(execPipe.fixImageRotate);
      }else{
        imgFunc.readFile(execPipe.compressImg);
      }
    }
  };
  execPipe.fixImageRotate = function(){
    console.log('[Pipe]FixRotate');
    imgFunc.fixImageRotate(execPipe.compressImg);
  };
  execPipe.compressImg = function(){
    console.log('[Pipe]compressImg');
    imgFunc.compressImg(execPipe.callImageEditer);
  };

  //通知图片编辑器进行编辑
  execPipe.callImageEditer = function(){
    console.log('[Pipe]callImageEditer');
    if(customEvents.callImageEditer != null){
      var imgInfo = toolFunc.cloneImage(_imageInfo.processImage);
      customEvents.callImageEditer(imgInfo, execPipe.commitEditImage);
    }else{
      execPipe.commitEditImage(null, null);    
    }
  };
  //完成图片编辑
  execPipe.commitEditImage = function(imgInfo){
    console.log('[Pipe]commitImage');
    if(imgInfo != null){
      imgFunc.updateImage(imgInfo, execPipe.beforeUpload);
    }else{
      _imageInfo.finalImage = toolFunc.cloneImage(_imageInfo.processImage);
      execPipe.beforeUpload();
    }
  };
  //准备上传图片, 可以修改图片属性
  execPipe.beforeUpload = function(){
    console.log('[Pipe]beforeUpload');
    if(customEvents.beforeUpload){
      var uploadSet = {
        'uploadUrl':options.uploadUrl,//上传地址
        'fileName':_imageInfo.finalImage.name,//文件名称
        'imageData':_imageInfo.finalImage.data,//图片数据
        'imageSize':_imageInfo.originImage.size,//图片数据
      };
      customEvents.beforeUpload(uploadSet);
      _imageInfo.finalImage.name = uploadSet.fileName;
      options.uploadUrl = uploadSet.uploadUrl;
    }
    toolFunc.showLoading();
    fileFunc.uploadFile(execPipe.afterUpload);
  };
  //完成图片上传
  execPipe.afterUpload = function(res){
    console.log('[Pipe]afterUpload');
    toolFunc.hideLoading();
    if(customEvents.afterUpload){
      customEvents.afterUpload(_imageInfo.finalImage, res);
    }
  };
  /*
   * 图片处理函数
   * */
  //检查文件基本信息
  imgFunc.checkFile = function(file){
    _imageInfo.file = file;
    _imageInfo.fileType = file.type;
    _imageInfo.originImage.name = file.name;
    _imageInfo.originImage.type = file.type;
    var ext = /\.[^\.]+$/.exec(file.name);
    if(ext.length > 0) _imageInfo.fileExt = ext[ext.length-1].toLowerCase();
    if(!(options.allowAnimation && _imageInfo.fileExt == '.gif')){
      _imageInfo.fileExt = '.jpg';
    }

    _imageInfo.originImage.name = file.name;
    if(!toolFunc.checkFileType(file)){
      toolFunc.catchError(1, 'check type failed');
      return false;
    }
    //检查文件大小(仅当图片为gif并且允许上传动画的时候进行检查)
    if(_imageInfo.fileExt == '.gif' && options.allowAnimation){
      if(!toolFunc.checkFileSize(file)){
        toolFunc.catchError(2, 'check size failed');
        return false;
      }
    }
    return true;
  };
  imgFunc.readFile = function(callback){
    var fr = new FileReader;
    fr.onload = function(e){
      _imageInfo.originImage.data = e.target.result;
      var image = new Image();
      image.onload = function(){
        _imageInfo.originImage.size = {
          'x':0, 'y':0, 'w':this.naturalWidth, 'h':this.naturalHeight, 
          'originWidth':image.width, 'originHeight':image.height,
        };
        _imageInfo.rotateStep = 0;
        _imageInfo.processImage = toolFunc.cloneImage(_imageInfo.originImage);
        _imageInfo.processImage.image = this;
        _imageInfo.processImage.name = options.imgNamePrefix+'-'
            +(new Date()).getTime()+'-'
            +Math.random().toString(36).substr(16)+_imageInfo.fileExt;
        _imageInfo.processImage.type = _imageInfo.fileType;
        callback();
      };
      image.src = _imageInfo.originImage.data;
    };
    fr.readAsDataURL(_imageInfo.file);
  };
  //修改iphone照片的旋转
  imgFunc.fixImageRotate = function(callback){
    EXIF.getData(_imageInfo.file, function() {
      EXIF.getAllTags(this);   
      var step = 0;
      var orientation = EXIF.getTag(this, 'Orientation');  
      if(orientation != "" && orientation != 1){
        switch(orientation){
          case 8: step++;
          case 3: step++;
          case 6: step++;
        }
        console.log("[UpdateImage]RotateStep="+step); 
      }
      _imageInfo.processImage.rotateStep = step;
      callback();
    });  
  };
  imgFunc.compressImg = function(callback){
    imageObj = _imageInfo.processImage;
    var zoomSize = imgFunc.getZoomSize(imageObj.size.w, imageObj.size.h, imageObj.rotateStep);
    var movePosition = imgFunc.getMovePosition(zoomSize.w, zoomSize.h, imageObj.rotateStep);
    var canvas = document.createElement("canvas");  
    var ctx = canvas.getContext("2d");  
    if(imageObj.rotateStep && imageObj.rotateStep%2==1){
      canvas.width = zoomSize.h;  
      canvas.height = zoomSize.w;  
    }else{
      canvas.width = zoomSize.w;  
      canvas.height = zoomSize.h;  
    }
    if(imageObj.rotateStep > 0){
      ctx.rotate(imageObj.rotateStep * 90 * Math.PI / 180);
    }
    //ctx.fillStyle="#FFFFFF";
    //ctx.fillRect(0,0,canvas.width,canvas.height); 
    ctx.save();
    ctx.drawImage(imageObj.image, movePosition.x, movePosition.y, zoomSize.w, zoomSize.h);
    _imageInfo.processImage.type = _imageInfo.fileType;//'image/jpeg';
    _imageInfo.processImage.data = canvas.toDataURL(_imageInfo.processImage.type, options.quality/10);
    _imageInfo.processImage.size ={'x':0, 'y':0, 'h':canvas.height, 'w':canvas.width };
    callback();
  };
  //更新图片，更新并压缩图片后的图片
  imgFunc.updateImage = function(size, callback){
    console.log('x:'+size.x+';y:'+size.y+';h:'+size.h+';w:'+size.w); 
    var img = new Image();
    img.onload = function(){
      _imageInfo.finalImage = toolFunc.cloneImage(_imageInfo.processImage);
      if(_imageInfo.fileExt == '.gif' && options.allowAnimation){
        _imageInfo.finalImage.size = {'x':0, 'y':0, 'w':size.w, 'y':size.y};
        _imageInfo.finalImage.isGif = true;
      }else{
        var canvas = document.createElement("canvas");  
        canvas.width = size.w;
        canvas.height = size.h;
        var ctx = canvas.getContext("2d");  
        ctx.drawImage(this, size.x, size.y, size.w, size.h, 0, 0, size.w, size.h);
        _imageInfo.finalImage.size = { 'x':0, 'y':0, 'w':size.w, 'h':size.h };
        _imageInfo.finalImage.data = canvas.toDataURL(_imageInfo.processImage.type, 1);
        _imageInfo.finalImage.isGif = false;
      }
      callback();
    };
    img.src = _imageInfo.processImage.data;
  };
  //获取图片缩放后的长宽，保证图片不会超过限制的最大长宽
  imgFunc.getZoomSize = function(width, height, rotateStep){
    var maxWidth = options.maxImageSize.w;
    var maxHeight = options.maxImageSize.h;
    var expectWidth = width;  
    var expectHeight = height;  
    if(rotateStep%2==1){
      expectWidth = height;
      expectHeight = width;
    }
    if(maxWidth > 0 && maxWidth < expectWidth) {
      expectHeight = expectHeight * (maxWidth / expectWidth);
      expectWidth = maxWidth;
    }
    if(maxHeight > 0 && maxHeight < expectHeight){
      expectWidth = expectWidth * (maxheight/expectHeight);
      expectHeight = maxHeight;
    }
    var zoomSize = {'w':expectWidth, 'h':expectHeight};
    if(rotateStep%2==1){
      zoomSize.w = expectHeight;
      zoomSize.h = expectWidth;
    }
    return zoomSize;
  };
  //图片需要移动的位置,保证修正后他的图片出现在画布上
  imgFunc.getMovePosition = function(width, height, rotateStep){
    var movePosition = {'x':0, 'y':0};
    switch(rotateStep){
      case 1:
        movePosition.y -= height;
        break;
      case 2:
        movePosition.x -= width;
        movePosition.y -= height;
        break;
      case 3:
        movePosition.x -= width;
        break;
    }
    return movePosition;
  };
  fileFunc.uploadFile = function(callback){
    console.log('upload file');
    var data = _imageInfo.finalImage.data;
    data = data.split(',')[1];
    data = window.atob(data);
    var ia = new Uint8Array(data.length);
    for (var i = 0; i < data.length; i++) {
        ia[i] = data.charCodeAt(i);
    };
    fileData = new Blob([ia], {
        type: _imageInfo.finalImage.type
    });
    _imageInfo.finalImage.fileData = fileData;
    if(customEvents.uploadFile){
      customEvents.uploadFile(_imageInfo.finalImage, callback);
    }else{
      alert('error');
    }
  };
  var $scUploadImage = this;
  this.bind = function(name, callback, params){
    switch(name) {
      case "catchError":
        customEvents.catchError = function(type, error){
          callback(type, error, params, $scUploadImage);
        };
        break;
      case "editImage":
        customEvents.callImageEditer = function(imageInfo, commitFunc){
          callback(imageInfo, commitFunc, params, $scUploadImage);
        };
        break;
      case "beforeUpload":
        customEvents.beforeUpload = function(imageInfo){
          callback(imageInfo, params, $scUploadImage);
        };
        break;
      case "uploadFile":
        customEvents.uploadFile = function(imageInfo, uploadComplete){
          callback(imageInfo, options.uploadUrl, uploadComplete, params, $scUploadImage);
        };
        break;
      case "afterUpload":
        customEvents.afterUpload = function(fileInfo, res){
          callback(fileInfo, res, params, $scUploadImage);
        };
        break;
      default:
      alert('scUploadImage bind an undefined event.');
    }
    return $scUploadImage;
  };
  this.bindImgEditer = function(callImgEditerFunc, params){
    customEvents.callImageEditer = function(imageInfo, commitFunc){
      callImgEditerFunc(imageInfo, commitFunc, params);
    };
  };

  //初始化设置
  toolFunc.init(uopts);
  var $fileCtrl = toolFunc.generateFCtrl();
  $fileCtrl.on('change', function(){
    if(this.files.length >0){
      execPipe.readFile(this.files[0]);
    }
  });
  //绑定触发事件
  this.each(function(){ $(this).on('click',function(){ $fileCtrl.click(); });});
  //绑定图片编辑器事件
  if(options.useImgEditer){
    this.bind('editImage', function(imgInfo, commitBack){
      console.log('调用cropper编辑器');
      var $cropperEditer = $('#_scimage_cropper');
      $cropperEditer.remove();
      $cropperEditer = $('<div id="_scimage_cropper" class="sc_image_editer"></div>');
      $cropperEditer.append('<img class="sc_image_editer_img"/>');
      $('body').append($cropperEditer);
      var $ctrlDiv = $('<div class="sc_image_editer_ctrl"></div>');
      $cropperEditer.append($ctrlDiv);
      $ctrlDiv.append($('<button type="button" class="sc_image_editer_commit">确定</button>'));
      $ctrlDiv.append($('<button type="button" class="sc_image_editer_cancel">取消</button>'));
      var $cropperCtrl = $cropperEditer.find('img.sc_image_editer_img');
      $cropperCtrl.attr('src', imgInfo.data);
      var cropperSet = {
        strict:false, responsive:false, zoomable:true, aspectRatio:1/1,
        touchDragZoom:true, cropBoxMovable:false, cropBoxResizeable:false,
        viewMode:1, dragMode:'move', movable:true
      };
      for(var key in options.cropperSet){
        if(typeof(cropperSet[key]) != 'undefined'){
          cropperSet[key] = options.cropperSet[key];
        }
      }
      $cropperCtrl.cropper(cropperSet);
      $commit = $cropperEditer.find('button.sc_image_editer_commit');
      $commit.unbind('click');
      $commit.on('click', function(){
        $cropperEditer.hide();
        var editedSize = $cropperCtrl.cropper('getData'); 
        imgInfo.size.x = editedSize.x;
        imgInfo.size.y = editedSize.y;
        imgInfo.size.w = editedSize.width;
        imgInfo.size.h = editedSize.height;
        commitBack(imgInfo.size);
      });
      $cancel = $cropperEditer.find('button.sc_image_editer_cancel');
      $cancel.unbind('click');
      $cancel.on('click', function(){
        $cropperEditer.hide();
      });
      $cropperEditer.show();
    }, null);
  } 
  if(options.uploadDrive=='alioss'){
    this.bind('uploadFile', function(imgInfo, uploadUrl, uploadComplete){
      var aliUpload = new scAliOssHandler({policyUrl:uploadUrl})
      .bind('uploadSucessful', function(res, callInfo){
        console.log('通过接口上传文件成功。');
        uploadComplete(res);
      })
      .bind('uploadFailed', function(res, callInfo){
        console.log('通过接口上传文件失败。');
        uploadComplete(null);
      });

      aliUpload.uploadFile(imgInfo.fileData, imgInfo.name, imgInfo.type, imgInfo);
    }, null);
  }
  //绑定文件上传器
  return $scUploadImage;
} }); })(jQuery);
