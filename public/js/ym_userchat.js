var ym_userchat = function(){
  var settings = {
    listCtrl    : '#chat_box',
    chatText    : '#chat_text',
    sendBtn     : '#send_msg',
    emptyItem   : '#empty_item',
    loadingItem : '#loading_item',
    itemFeature : '.dialog-list',
    loadSize    : 3,
    targetUser  : 0
  };
  var ctrlCache = {
    listCtrl  : null,
    chatText  : null,
    sendBtn   : null,
    emptyItem : null,
  };
  var status ={
    beginId       : 0,
    endId         : 0,
    touchFlag     : false,
    touchPosition : 0,
    touchMove     : 0,
    noMore        : false,
    token         : $.ymFunc.getToken()
  };
  var funcs = {};
  funcs.sendChat = function(){};
  funcs.loadHistory = function() {
    $.get('/message/msg-history/'+settings.targetUser+'-'+status.endId+'-'+settings.loadSize, function(data){
      var newItems = $(data);
        var i = 0;
        for(i=0; i<newItems.length; i++){
          funcs.addItem(newItems[i], 'top');
        }
        if(status.endId == 0 && newItems.length == 0){
          ctrlCache.emptyItem.show();
        }else{
          status.endId = $(newItems[i-1]).attr('msgId');
        }
        if(newItems.length < settings.loadSize){
          funcs.hideLoading();
          status.noMore = true;
        }
    });
  };
  funcs.getNew = function() {};
  funcs.addItem = function(item, position){
    var archor = $(settings.itemFeature).last();
    if(position == 'top'){
      $(settings.emptyItem).after(item);
    }else{
      ctrlCache.listCtrl.append(item);
    }
    funcs.moveScroll(position);
  };
  funcs.moveScroll = function(position){
    var ctrl = ctrlCache.listCtrl;
    if(position == 'top'){
      ctrl.scrollTop(0);
    }else{
      var y = ctrl[0].scrollHeight - ctrl.height();
      ctrlCache.listCtrl.scrollTop(y);
    }
  }
  funcs.showLoading = function(){
    ctrlCache.loadingItem.show();
  };
  funcs.resizeLoading= function(){
    ctrlCache.loadingItem.animate({'height':'25px'},150); 
  };
  funcs.hideLoading = function(){
    ctrlCache.loadingItem.hide();
  };
  this.bind = function(opt) {
    for(var st in opt){
      settings[st] = opt[st];
    }
  };
  this.begin = function(){
    //缓存涉及的控件
    ctrlCache.listCtrl = $(settings.listCtrl);
    ctrlCache.sendBtn  = $(settings.sendBtn);
    ctrlCache.chatText = $(settings.chatText);
    ctrlCache.emptyItem = $(settings.emptyItem);
    ctrlCache.loadingItem = $(settings.loadingItem);
    ctrlCache.listCtrl.on('touchstart', function(e){
      status.touchFlag = !status.noMore;
      status.touchMove = 0;
      status.touchPosition = e.originalEvent.touches[0].clientY;
    });
    ctrlCache.listCtrl.on('touchmove', function(e){
      if(!status.touchFlag) return;
      var move = e.originalEvent.touches[0].clientY - status.touchPosition;
      var isDown = status.touchPosition < e.originalEvent.touches[0].clientY;
      if(isDown && ctrlCache.listCtrl[0].scrollTop == 0){
        ctrlCache.loadingItem.css('height', move); 
        status.touchMove = move;
        ctrlCache.loadingItem.show(); 
      }
    });
    ctrlCache.listCtrl.on('touchend', function(e){
      status.touchFlag = false;
      if(status.touchMove > 60){
        funcs.loadHistory();
      }
      funcs.resizeLoading();
    });
    ctrlCache.loadingItem.on('click', function(){
      funcs.loadHistory();
    });
    ctrlCache.sendBtn.on('click', function(){
      var msg = $.trim(ctrlCache.chatText.text());
      if(msg == '') return;
      $.post('/message/add', {
        '_token':status.token,
        'msg':msg,
        'userId':settings.targetUser
      }, function (data){
        if(data.res){
          funcs.addItem($(data.html), 'bottom');
        }
        ctrlCache.chatText.text('');
      });
    });
    ctrlCache.sendBtn.on('keydown paste', function(event) {
      if($(this).text().length >= $(this).attr('maxlength') && event.keyCode != 8) { 
        event.preventDefault();
      }
    });
    funcs.loadHistory();
  };
};
var $USERCHAT = new ym_userchat();
