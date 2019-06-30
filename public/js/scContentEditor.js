(function($){$.extend($.fn, { scContentEditor:function(uopts){
  var options = {};  //配置参数
  var model = 0;   //实时状态, 0: 内容编辑模式 ，1：段落编辑模式
  var funcs = {};    //内部函数
  var editors = {}; //编辑器
  var tools = {}; //编辑器
  var paragraphs = [];
  var $content = this; //自身别名
  //-- 段落类
  var paragraph = function(val){
    // 属性
    var setting = {status:0};
    var parFunc = {};
    var $par    = this;
    var parEditor = null;
    parFunc.init = function(value){
      for(var key in value){
          setting[key] = value[key];
      }
      setting.isLock = false;
      switch(setting.type){
        case "text":
          setting.obj = $('<div class="sc_ce_par_text"><pre></pre></div>');
          if(setting.status == 1 || setting == 11){
            setting.obj.addClass('is_bold');
          }
          if(setting.status == 10 || setting == 11){
            setting.obj.addClass('is_ref');
          }
          break;
        case "image":
          setting.obj = $('<div class="sc_ce_par_image"><img src="" /><label></label></div>');
          if(setting.status == 1){
            setting.obj.addClass('is_fit');
          }
          break;
        case "link":
          setting.obj = $('<div class="sc_ce_par_link"><span href="" ></span></div>');
          break;
      }
      setting.obj.on('click', function(){
        if(setting.isLock) return;
        var value = $par.getValue();
        editors.getEditor(setting.type).show(value, parFunc.updateValue);
      });
      parFunc.updateValue($par.getValue());
    };
    parFunc.updateValue = function(value){
      switch(setting.type){
        case "text":
          setting.obj.find('pre').text(value.text);
          setting.text = value.text;
          break;
        case "image":
          setting.obj.find('label').text(value.text);
          setting.obj.find('img').attr('src', value.url);
          setting.text = value.text;
          setting.url = value.url;
          break;
        case "link":
          setting.obj.find('span').text(value.text);
          setting.obj.find('span').attr('href', value.url);
          setting.text = value.text;
          setting.url = value.url;
          break;
      }
    };
    parFunc.makeMoveModel = function(){
        setting.obj.addClass('sc_ce_par_shot');
        setting.obj.find('div.sc_ce_par_shade').remove();
        setting.obj.find('i').remove();
        setting.obj.append('<div class="sc_ce_par_shade"></div>');
        setting.obj.attr('oheight', setting.obj.outerHeight());
        setting.obj.animate({height:"55px"}, 200);

        setting.obj.append('<i class="ymicon-order-up sc_ce_par_icon"></i>');
        setting.obj.append('<i class="ymicon-order-down sc_ce_par_icon"></i>');
        setting.obj.append('<i class="ymicon-delete sc_ce_par_icon"></i>');

        setting.obj.find('i.ymicon-order-up').on('click', parFunc.moveUp);
        setting.obj.find('i.ymicon-order-down').on('click', parFunc.moveDown);
        setting.obj.find('i.ymicon-delete').on('click', parFunc.makeDeleteModel);

        if(setting.type == 'image'){
          if(setting.status == 1){
            setting.obj.append('<i class="ymicon-fit sc_ce_par_icon ym_active"></i>');
          }else{
            setting.obj.append('<i class="ymicon-fit sc_ce_par_icon"></i>');
          }
          setting.obj.find('i.ymicon-fit').on('click', parFunc.switchFit);
        }
        if(setting.type == 'text'){
          if(setting.status == 1 || setting.status == 11){
            setting.obj.append('<i class="ymicon-bold sc_ce_par_icon ym_active"></i>');
          }else{
            setting.obj.append('<i class="ymicon-bold sc_ce_par_icon"></i>');
          }
          if(setting.status == 10 || setting.status == 11){
            setting.obj.append('<i class="ymicon-reference sc_ce_par_icon ym_active"></i>');
          }else{
            setting.obj.append('<i class="ymicon-reference sc_ce_par_icon"></i>');
          }
          setting.obj.find('i.ymicon-reference').on('click', parFunc.switchReference);
          setting.obj.find('i.ymicon-bold').on('click', parFunc.switchBold);
        }
    };
    parFunc.makeEditModel = function(){
        setting.obj.removeClass('sc_ce_par_shot');
        setting.obj.find('div.sc_ce_par_shade').remove();
        setting.obj.animate({height:setting.obj.attr('oheight')+'px'}, 200, function(){
          setting.obj.css('height', 'auto');
        });
        setting.obj.find('i').remove();
    };
    parFunc.makeDeleteModel = function(){
        setting.obj.find('div.sc_ce_par_shade').remove();
        setting.obj.append('<div class="sc_ce_par_shade for_rm"></div>');
        setting.obj.find('i').remove();
        setting.obj.append('<i class="ymicon-yes sc_ce_par_icon"></i>');
        setting.obj.append('<i class="ymicon-no sc_ce_par_icon"></i>');
        setting.obj.append('<i class="sc_ce_par_confirm">确定要删除这个段落吗？</i>');
        setting.obj.find('i.ymicon-no').on('click', parFunc.makeMoveModel);
        setting.obj.find('i.ymicon-yes').on('click', parFunc.remove);
    }
    parFunc.moveUp = function(){
      var prev = setting.obj.prev();
      if(prev.length == 0 ) return;
      parFunc.exchangeElement(prev, setting.obj);
      funcs.moveParagraph(setting.order, true) ;   
    };
    parFunc.moveDown = function(){
      var next = setting.obj.next();
      if(next.length == 0 ) return;
      parFunc.exchangeElement(setting.obj, next);
      funcs.moveParagraph(setting.order, false) ;   
    };
    parFunc.exchangeElement = function(prev,next){
      var prevTop = prev.position().top;
      var nextTop = next.position().top;
      prev.animate({top:'65px'}, 200, function(){
        prev.insertAfter(next);
        prev.css('top', 'auto');
      });
      next.animate({top:'-65px'}, 200, function(){
        next.css('top', 'auto');
      });
    };
    parFunc.remove = function(){
      setting.obj.animate({width:0}, 300, function(){
        setting.obj.remove();
        funcs.removeParagraph(setting.order);
      });
    };
    parFunc.switchBold = function(){
      parFunc.switchIconStatus(this);
      var res = parFunc.switchParStatus('is_bold');
      if(res){
        setting.status += 1;
      }else{
        setting.status += -1;
      }
    }
    parFunc.switchReference = function(){
      parFunc.switchIconStatus(this);
      var res = parFunc.switchParStatus('is_ref');
      if(res){
        setting.status += 10;
      }else{
        setting.status += -10;
      }
    }
    parFunc.switchFit = function(){
      parFunc.switchIconStatus(this);
      var res = parFunc.switchParStatus('is_fit');
      if(res){
        setting.status += 1;
      }else{
        setting.status += -1;
      }
    }
    parFunc.switchIconStatus = function(icon){
      if($(icon).hasClass('ym_active')){
        $(icon).removeClass('ym_active');
      }else{
        $(icon).addClass('ym_active');
      }
    }
    parFunc.switchParStatus = function(cla){
      if(setting.obj.hasClass(cla)){
        setting.obj.removeClass(cla);
        return false;
      }else{
        setting.obj.addClass(cla);
        return true;
      }
    }
    this.getValue = function(){ return {
      id     : setting.id,
      text   : setting.text,
      url    : setting.url,
      type   : setting.type,
      order  : setting.order,
      status : setting.status
    }; };
    this.getObj = function(){ return setting.obj };
    this.updateValue = function(value){ parFunc.updateValue(value); };
    this.setOrder = function(order){ setting.order = order;};
    this.getOrder = function(){return setting.order; };
    this.switchLock = function(isLk){
      setting.isLock = isLk;
      if(setting.isLock){
        parFunc.makeMoveModel();
      }else{
        parFunc.makeEditModel();
      }
      return setting.isLock;
    };
    parFunc.init(val);
  };
  //--end 段落类
  //--编辑器类
  var baseEditor = function(type){
    var shade   = $('<div class="ym_shade" style="z-index:1000"></div>');
    var form    = $('<div class="sc_ce_editor"></div>');
    var confirm = $('<button class="sc_ce_editor_confirm">确定</button>'); 
    var cancel  = $('<button class="sc_ce_editor_cancel">取消</button>'); 
    var p_type = 'text';
    var editorCtrl = null;
    var editFuncs = {};
    editFuncs.init = function(){
      switch(type){
        case "text":
          editorCtrl = new textEditorCtrl();
          break;
        case "image":
          editorCtrl = new imageEditorCtrl();
          break;
        case "link":
          editorCtrl = new linkEditorCtrl();
          break;
      }
      p_type = type;
    };
    editFuncs.hide = function(){
      shade.hide();
      form.hide();
    };
    editFuncs.check = function(value){
      var res = false;
      switch(p_type) {
        case 'text':
          res = value.text != '';
          break;
        case 'image':
          res = value.url != '';
          break;
        case 'link':
          res = (value.text != '' && value.url != '');
          break;
      }
      form.find('div').removeClass('error');
      if(!res){
        form.find('div').addClass('error');
      }
      return res;
    };
    this.show = function(value, callBack){
      var body = $('body');
      if(body.find('sc_ce_editor').length == 0){
        body.append(shade);
        body.append(form);
      }
      form.html('');
      editorCtrl.setValue(value);
      form.append(confirm);
      form.append(cancel);
      var doms = editorCtrl.getDom();
      for(var i=0; i<doms.length; i++){
        form.append($(doms[i]));
      }
      cancel.on('click', editFuncs.hide);
      shade.on('click', editFuncs.hide);
      confirm.on('click', function(){
        var value = editorCtrl.getValue();
        if(!editFuncs.check(value)){
          return;
        }
        if(typeof(callBack) == 'function'){
          callBack(value);
        }
        editFuncs.hide();
      });
      shade.show();
      form.show();
    };
    var textEditorCtrl = function(){
      var txtCtrl = $('<div class="long_text"><textarea placeholder="请填写文字"></textarea></div>'); 
      this.getDom   = function(){
        return txtCtrl;
      };
      this.setValue = function(value){
        txtCtrl.find('textarea').val(value.text);
      };
      this.getValue = function(){
        return {text:txtCtrl.find('textarea').val()};
      };
    };
    var imageEditorCtrl = function(){
      var imgCtrl = $('<div class="img_box"><img src="" /></div>');
      var labelCtrl = $('<div class="label_text"><input type="text" value="" placeholder="图片注释" /></div>');
      var changeIcon = $('<i class="ymicon-img"></i>');
      this.getDom = function(){
        imgCtrl.append(changeIcon);
        changeIcon.scUploadImageWork({
          name:'production',
          maxFileSize:5,
          allowAnimation:true,
          useImgEditer:false,
          uploadUrl:'/img/policy',
        }).bind('beforeUpload', function(uploadSet, params){
             uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/production';
        }, null).bind('afterUpload', function(imgInfo, res){
           if(res == null){
               alert('上传失败了。');
           }else{
             imgCtrl.find('img').attr('src', 'http://img.umeiii.com/production/'+imgInfo.name);
           }
         }, null);
        return [imgCtrl, labelCtrl];

      };
      this.setValue = function(value){
        imgCtrl.find('img').attr('src', value.url);
        labelCtrl.find('input').val(value.text);
      };
      this.getValue = function(){
        return {
          url  : imgCtrl.find('img').attr('src'),
          text : labelCtrl.find('input').val()
        };
      };
    };
    var linkEditorCtrl = function(){
      var urlCtrl = $('<div class="link_text"><textarea placeholder="请填写链接地址"></textarea></div>');
      var inputCtrl = $('<div class="input_text"><input type="text" placeholder="请填写链接名称" value="" /></div>');
      this.getDom = function(){
        return [urlCtrl, inputCtrl];

      };
      this.setValue = function(value){
        urlCtrl.find('textarea').val(value.url);
        inputCtrl.find('input').val(value.text);
      };
      this.getValue = function(){
        return {
          url  : urlCtrl.find('textarea').val(),
          text : inputCtrl.find('input').val()
        };
      };
    };
    editFuncs.init(); 
  };
  //单例话编辑器
  editors.getEditor = function(type){
    if(typeof(editors[type]) == 'undefined'){
      editors[type] = new baseEditor(type);
    }
    return editors[type];
  };
  //--end 编辑器类
  funcs.init = function(){
    //初始化内容区和状态栏
    $($content).addClass('sc_ce');
    $($content).addClass('edit');
    tools.bar = $('<div class="ym_footerbar"><ul class="ym_avg_4"></ul></div>');
    $('body').append(tools.bar);
    funcs.addTextButton();
    funcs.addImageButton();
    funcs.addLinkButton();
    funcs.addSwitchButton();
    funcs.showEmpty();
  };
  funcs.addTextButton = function(){
    tools.addText = $('<li class="am-active">' +
             '<a href="javascript:void(0)">' +
             '<i class="ymicon-t-list"></i> <br /> <span>添加文字</span>' +
             '</a></li>');
    tools.bar.find('ul').append(tools.addText);
    tools.addText.on('click', function(){
      editors.getEditor('text').show({text:''}, function(value){
        value.type='text';
        value.id = 0;
        funcs.appendParagraph(value);
      });
    });
  };
  funcs.addImageButton = function(){
    tools.addImage = $('<li class="am-active">' +
             '<a href="javascript:void(0)">' +
             '<i class="ymicon-img"></i> <br /> <span>添加图片</span>' +
             '</a></li>');
    tools.bar.find('ul').append(tools.addImage);
    tools.addImage.on('click', function(){
      editors.getEditor('image').show({text:'', url:''}, function(value){
        value.type='image';
        value.id = 0;
        funcs.appendParagraph(value);
      });
    });
    return;
  };
  funcs.addLinkButton = function(){
    tools.addLink = $('<li class="am-active">' +
             '<a href="javascript:void(0)">' +
             '<i class="ymicon-share"></i> <br /> <span>添加链接</span>' +
             '</a></li>');
    tools.bar.find('ul').append(tools.addLink);
    tools.addLink.on('click', function(){
      editors.getEditor('link').show({text:'', url:''}, function(value){
        value.type='link';
        value.id = 0;
        funcs.appendParagraph(value);
      });
    });
  };
  funcs.addSwitchButton = function(){
    tools.switchModel = $('<li class="am-active">' +
             '<a href="javascript:void(0)">' +
             '<i class="ymicon-setting"></i> <br /> <span>编辑段落</span>' +
             '</a></li>');
    tools.bar.find('ul').append(tools.switchModel);
    tools.switchModel.on('click', function(){
      var isLock = model == 1;
      model = isLock?0:1;
      for(var i=0; i<paragraphs.length; i++){
        paragraphs[i].switchLock(!isLock);
      }
      $(this).find('span').text(isLock?'编辑内容':'编辑段落');
    });
  };
  funcs.appendParagraph = function(value){
      var par = new paragraph(value);
      par.setOrder(paragraphs.length);
      $($content).append(par.getObj());
      paragraphs.push(par);
      funcs.hideEmpty();
  };
  funcs.moveParagraph = function(order, isUp){
    if(order == 0 && isUp) return;
    if(order == paragraphs.length - 1 && !isUp) return;
    var originPar = paragraphs[order];
    paragraphs[order] = paragraphs[isUp?(order-1):(order+1)];
    paragraphs[isUp?(order-1):(order+1)] = originPar;
    paragraphs[order].setOrder(order); 
    paragraphs[isUp?(order-1):(order+1)].setOrder(isUp?(order-1):(order+1));
  };
  funcs.removeParagraph = function(order){
    for(var i = order+1; i<paragraphs.length; i++) {
      paragraphs[i].setOrder(paragraphs[i].getOrder()-1);
    }
    paragraphs.splice(order,1);
    if(paragraphs.length == 0){
      funcs.showEmpty();
    }
  };
  funcs.showEmpty = function(){
     $($content).append('<div class="sc_ce_par_empty">请添加内容</div>');
  };
  funcs.hideEmpty = function(){
    $($content).find('.sc_ce_par_empty').remove();
  };
  // public 方法
  this.loadValue = function(values){
    for(var i=0; i< values.length; i++){
      funcs.appendParagraph(values[i]);
    }
  };
  this.getValue = function(){
    var res = [];
    for(var i=0; i< paragraphs.length; i++){
     res.push(paragraphs[i].getValue());
    }
    return res;
  };
  this.bindEvent = function(eventName, eventFunc){
    if(eventName == 'save'){
      options.onSave = eventFunc;
    }
    if(eventName == 'remove'){
      options.onRemove = eventFunc;
    }
  };
  //--初始化--
  funcs.init();
  return $content;
} }); })(jQuery);
