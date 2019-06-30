(function($){$.extend($.fn, { ymSelectControl:function(uopts){
  var options = {
    name:'def',
    title:'选择内容',
    multiple:false,
    columns:1,
    style:0
  };
  
  var $selectCtrl = this;
  var $pageCtrl = null;
  var loadStatus = false;

  var toolFunc = {};
  toolFunc.init = function(opts){
    for(var key in opts){
      if(typeof(options[key]) != 'undefined'){
        options[key] = opts[key];
      }
    }
    if(typeof(opts.dataList) == 'object'  && opts.dataList.constructor != Array){
      options.dataList = [];
      for(key in opts.dataList){
        options.dataList.push({
          'value' : key,
          'text'  : opts.dataList[key],
          'desc'  : ''
        });
      }
    }else{
      options.dataList = opts.dataList;
    }
  };
  toolFunc.getPageCtrl = function(){
    if($pageCtrl == null){
      var pageCtrlName = 'ymsc_'+name+'_page';
      $pageCtrl = $('<div class="ym_select_ctrl" id="'+pageCtrlName+'"></div>');
      
      var $value = $('<input type="hidden" value="" />');
      $pageCtrl.append($value);

      var $headerbar = $('<div class="ym_headerbar"><ul class="am-avg-sm-3"></ul></div>');
      $pageCtrl.append($headerbar);

      var $backBtn = $('<li class="ym_headerbar_left"><i class="ymicon-left" style="font-size:14px"></i><span class="ym_backheader_btn">&nbsp;&nbsp;返回</span></li>');
      $backBtn.on('click', function(){eventFunc.cancel();});
      $headerbar.find('ul').append($backBtn);

      var $title=$('<li class="ym_headerbar_center"><span class="ym_headerbar_title">'+options.title+'</span></li>');
      $headerbar.find('ul').append($title);

      if(options.multiple){
        var $commitBtn = $('<li class="ym_headerbar_right"><span class="ym_backheader_btn">确定</span><i class="ymicon-right" style="font-size:14px"></i></li>');
        $commitBtn.on('click', function(){ eventFunc.saveValue(); });
        $headerbar.find('ul').append($commitBtn);
      }else{
        var $commitBtn = $('<li class="ym_headerbar_right">&nbsp;</li>');
        $headerbar.find('ul').append($commitBtn);
      }
      var $content = $('<ul class="content avg-'+options.columns+'"></ul>');
      $pageCtrl.append($content);
      $('body').append($pageCtrl);
    }
    return $pageCtrl;
  };
  toolFunc.getContent = function(){
    var pageCtrl = toolFunc.getPageCtrl();
    var $content = pageCtrl.find('ul.content');
    return $content;
  };
  toolFunc.loadItems = function(){
    var dataType = typeof(options.dataList);
    if(dataType == 'object'){
      toolFunc.appendItems(options.dataList);
    }else if(dataType == 'string'){
      $.get(dataType, function(data){
        toolFunc.appendItem(data);
      });
    }else if(dataType == 'function'){
      var data = dataList(selectCtrl);
      toolFunc.appendItem(data);
    }
  };
  toolFunc.appendItems = function(data){
    var $content = toolFunc.getContent();
    $content.html('');
    for(var i=0; i<data.length;i++){
      var value = data[i].value;
      var text = data[i].text;
      var $item;
      if(options.style == 0){
        $item = $('<li value="'+value+'" class="item">'+text+'</li>');
      }else{
        var desc = data[i].desc;
        $item = $('<li value="'+value+'" class="detaiItem"></li>');
        $item.append('<label>'+text+'</label>');
        $item.append('<span>'+desc+'</span>');
      }
      $item.on('click', function(){
       var value = $(this).attr('value');
        toolFunc.selectItem(value);
        if(!options.multiple){
          eventFunc.saveValue();
        }
      });
      $content.append($item);
    }
    for(var key in data){
    }
  }
  toolFunc.selectItem = function(value){
    var $content = toolFunc.getContent();
    var $item =$content.find('li[value="'+value+'"]');
    if(options.multiple){
      $item.removeClass('selected'); 
    }else{
      $content.find('li').removeClass('selected'); 
    }
    $item.addClass('selected'); 
  };
  toolFunc.getValue = function(){
    var $content = toolFunc.getContent();
    var value = [];
    $content.find('li.selected').each(function(){
      var item = $(this);
      var v = item.attr('value');
      for(var i=0; i<options.dataList.length;i++){
        if(options.dataList[i].value == v){
          value.push(options.dataList[i]);
          break;
        }
      }
    });
    return value;
  };
  toolFunc.show = function(){
    var $pageCtrl = toolFunc.getPageCtrl();
    $pageCtrl.show();//动画显示
    if(!loadStatus){
      toolFunc.loadItems();
    }
    var value = eventFunc.getValue();
    if(options.multiple){
     for(var i=0;i<value.length;i++) {
       toolFunc.selectItem(value[i]);
     }

    }else{
      toolFunc.selectItem(value);
    }
  };
  toolFunc.hide = function(){
    var pageCtrl = toolFunc.getPageCtrl();
    pageCtrl.hide();
  };
  var eventFunc = {};
  eventFunc.cancel = function(){
    toolFunc.hide();
  };
  eventFunc.getValue = function(){
    var value = eventBind.getValue();
    return value;
  };
  eventFunc.saveValue = function(){
    var value = toolFunc.getValue();
    if(options.multiple){
      eventBind.setValue(value);
    }else{
      if(value.length >0){
        eventBind.setValue(value[0]);
      }else{
        eventBind.setValue({'value':'', 'text':''});
      }
    }
    toolFunc.hide();
  };
  var eventBind={
    getValue:function(){},
    setValue:function(value){},
  };
  toolFunc.init(uopts);
  this.each(function(){ $(this).on('click',function(){ 
    toolFunc.show();
  });});
  this.bind = function(funcName, func) {
    if(funcName == 'getValue'){
      eventBind.getValue = func;
    }else if(funcName == 'setValue'){
      eventBind.setValue = func;
    }
    return $selectCtrl;
  };
  this.select = function(value){
    var v = null;
    for(var i=0;i<options.dataList.length;i++){
      var item = options.dataList[i];
      if(item.value == value){
        eventBind.setValue(item);     
        break;
      }
    }
  };
  return $selectCtrl;
} }); })(jQuery);

