(function($){$.extend($.fn, { commonDialog:function(uopts){
  
  var options = {
    type:'',//对话框种类
    content:'',//显示内容
    value:'',//显示数值
  };


//用户绑定的事件
  var customEvents = {
    confirmDialog:null,
    confirmAndCancelDialog:null,
    eidtDialog:null
  };
  //用户弹出框
  var dialogView = {
    confirmDialog:null,
    confirmAndCancelDialog:null,
    eidtDialog:null
  }
  //工具函数
  var toolFunc = {};
  toolFunc.init = function(userOpts){
    if (typeof(userOpts) != 'object') return;
    for(var key in userOpts){
      if (typeof(options[key]) != 'undefined') {
        options[key] = userOpts[key];
      }
    }


  }
  //初始化
  toolFunc.init(uopts);

  var $commonDialog = this;
  this.bind = function(name, callback, params){
    console.log("[bind1]"+name+";params"+params);
    switch(name){

      //确认对话框
      case "confirmDialog":
        customEvents.confirmDialog = function(){
           callback(params, $commonDialog);
        };
        break;
      //确认取消对话框
      case "confirmAndCancelDialog":
        customEvents.confirmAndCancelDialog = function(){
          callback(params, $commonDialog);
        };
        break;
      //编辑对话框
      case "eidtDialog":
        customEvents.eidtDialog = function(value){
          callback(params, value, $commonDialog);
        };
        break;
    }
    return $commonDialog;
  };
  //确认对话框
  toolFunc.confirmDialog = function(){
    console.log('[confirmDialog]');
    if (customEvents.confirmDialog != null) {
      customEvents.confirmDialog();
    }
  }
  //确认取消对话框
  toolFunc.confirmAndCancelDialog = function(){
    console.log('[confirmAndCancelDialog]');
    if (customEvents.confirmAndCancelDialog != null) {
      customEvents.confirmAndCancelDialog();
    }
  }
  //编辑对话框
  toolFunc.eidtDialog = function(value){
    console.log('[eidtDialog]');
    if (customEvents.eidtDialog != null) {
      customEvents.eidtDialog(value);
    }
  }

  this.each(function(){ 
    $(this).on('click',function(){
      
      switch(options.type){

      //确认对话框
      case "confirmDialog":
        $mainBody = dialogView.confirmDialog();
        dialogShow($mainBody);
        break;
      //确认取消对话框
      case "confirmAndCancelDialog":
        $mainBody = dialogView.confirmAndCancelDialog();
        dialogShow($mainBody);
        break;
      //编辑对话框
      case "eidtDialog":
        $mainBody = dialogView.eidtDialog();
        dialogShow($mainBody);
        break;
      }
    });
  });
//弹出对话框
function dialogShow(dialog){
    $mainBody = dialog;
    $mainBody.show();
    var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
    var yd = $mainBody.find('.ym-dialog');
    yd.css('top',0+scrolltop-(yd.height()/2));
    $('body').addClass('ym-body-ovfHidden');

}

//确认画面生成
  dialogView.confirmDialog = function(content){
    var $shade = $('#dialogShade');
    $shade.remove();
    $shade = $('<div id="dialogShade" class="ym-dimmer"></div>');
    $('body').append($shade);
    var $mainBody = $('#commonConfirm');
      $mainBody.remove();
      $mainBody = $('<div class="ym-dimmer" id="commonConfirm">'+
                    '<div class="ym-dialog">'+
                    '<div class="ym-dialog-content" style="padding: 2rem 2rem;">'+
                    '<div class="ym-dialog-hd" style="text-align:center;background:url(/imgs/common_dialog_header.png) no-repeat 10px 0px" ></div>'+
                    '<div style="text-align:center;color:#929292;font-size:1.2rem;">'+options.content+'</div>'+
                    '</div>'+
                    '<div class="ym-dialog-footer">'+
                    '<span id="dialog-confirm" class="ym-btn" style="color:#474747;">确  定</span>'+
                    '</div></div></div>');


      $('body').append($mainBody);
      $confirm = $mainBody.find('#dialog-confirm');
      $confirm.on('click', function(){
        console.log('confirm');
        dialogCancel($mainBody, $shade);
      });
      //对话框以外区域
      $shade.on('click',function(){
        dialogCancel($mainBody, $shade);
      });

      return $mainBody;
  }

  //编辑画面生成
  dialogView.eidtDialog = function(){
    var $shade = $('#dialogShade');
    $shade.remove();
    $shade = $('<div id="dialogShade" class="ym-dimmer"></div>');
    $('body').append($shade);
    var $mainBody = $('#commonConfirm');
      $mainBody.remove();
      $mainBody = $('<div class="ym-dimmer" id="commonConfirm">'+
                    '<div class="ym-dialog">'+
                    '<div class="ym-dialog-content">'+
                    '<div class="ym-dialog-hd" style="background:url(/imgs/common_dialog_header.png) no-repeat 10px -140px;float:left;text-align:left;margin-left:1rem;height:100px;"></div>'+
                    '<div style="padding-left:3rem;">'+
                    '<div class="ym-dialog-bd" style="text-align:left;">'+options.content+'</div>'+
                    '<div class="ym-dialog-content-right">'+
                    '<input type="text" name="value" />'+
                    '<span>元</span>'+
                    '</div></div></div>'+
                    '<div class="ym-footer">'+
                    '<span id="dialog-confirm" class="ym-btn">确  定</span>'+
                    '</div></div></div>');

      $('body').append($mainBody);
      $confirm = $mainBody.find('#dialog-confirm');
      if (options.value) {
        $('input[name=value]').attr('value', options.value);
      };
      
      $confirm.on('click', function(){
        toolFunc.eidtDialog($('input[name=value]').val());
        console.log('input[name=value]:'+$('input[name=value]').val());
      });
      //对话框以外区域
      $shade.on('click',function(){
        dialogCancel($mainBody, $shade);
      });
      return $mainBody;
  }
  //确认取消画面生成
  dialogView.confirmAndCancelDialog = function(){
    var $shade = $('#dialogShade');
    $shade.remove();
    $shade = $('<div id="dialogShade" class="ym-dimmer"></div>');
    $('body').append($shade);
    var $mainBody = $('#commonConfirm');
      $mainBody.remove();
      $mainBody = $('<div class="ym-dimmer" id="commonConfirm">'+
                    '<div class="ym-dialog">'+
                    '<div class="ym-dialog-content" style="padding: 2rem 2rem;">'+
                    '<div class="ym-modal-hd" style="width:80px;height:70px;overflow:hidden;display:inline-block;background:url(/imgs/common_dialog_header.png) no-repeat 10px -70px" ></div>'+
                    '<div class="ym-modal-bd" id="my-confirm-delete-content" style="color:#929292;font-size:1.2rem;"><span>'+options.content+'</span></div>'+
                    '</div>'+
                    '<div class="ym-dialog-footer">'+
                    '<span id="dialog-confirm" class="ym-btn" style="color:#474747;display:block;float:left;width:50%">确  定</span>'+
                    '<span id="dialog-cancel" class="ym-btn" style="color:#474747;display:block;float:right;width:50%">取  消</span>'+
                    '</div></div></div>');


      $('body').append($mainBody);
      $confirm = $mainBody.find('#dialog-confirm');
      $confirm.on('click', function(){
        console.log('confirm');
        toolFunc.confirmAndCancelDialog();
        dialogCancel($mainBody, $shade);
      });
      $cancel = $mainBody.find('#dialog-cancel');
      $cancel.on('click', function(){
        dialogCancel($mainBody, $shade);
        console.log('cancel');
      });
      //对话框以外区域
      $shade.on('click',function(){
        dialogCancel($mainBody, $shade);
      });
      return $mainBody;
  }

  //取消弹出框
  function dialogCancel(dialogBody, dialogShade){
      $mainBody  = dialogBody;
      $shade = dialogShade;
      $shade.remove();
      $mainBody.hide();
      $('body').removeClass('ym-body-ovfHidden');

  }
  //绑定弹出框
  return $commonDialog;
} }); })(jQuery);
