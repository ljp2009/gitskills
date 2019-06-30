jQuery.ymPublish = {};
jQuery.ymPublish.bindField = {};
jQuery.ymPublish.settings = {
  'mainContent':'#mainPage',
  'partContent':'#editPage'
};
jQuery.ymPublish.set = function(obj){};
/*
 * @name 字段标记;
 * @obj 字段设置{'partName', 'onSave', 'onFinish', 'onShow'}
* */
jQuery.ymPublish.addBind = function(name, obj){
  $.ymPublish.bindField[name] = obj;
};
jQuery.ymPublish.back = function(){
    if(!$($.ymPublish.settings.mainContent).is(':visible')){
        showEditMainPage();
    }
};
jQuery.ymPublish.save = function(value, paramName){};
jQuery.ymPublish.finish = function(value, paramName){};

jQuery.ymPublish.load = function(fun){
  if(typeof(fun) == 'function'){ fun(); }
  $.ymPublish._showMainPartview();
};
jQuery.ymPublish.showPicklistPartview = function(listName, fieldName, defValue){
  $.get('/picklist/'+listName+'/'+fieldName, function(listView){
    $($.ymPublish.settings.partContent).html(listView);
    picklist_selected(defValue);
  });
};

jQuery.ymPublish._showEditPartView = function(field){
    var mainPart = $($.ymPublish.settings.partContent);
    mainPart.css('z-index','1');
    mainPart.fadeOut("slow");
    var showPart = $($.ymPublish.settings.mainContent);
    showPart.css('z-index','999');
    showPart.css('right','-100%');
    showPart.show();
    field.onShow();
    showPage.animate({ right:'0'});
};
jQuery.ymPublish._showMainPartview = function(){
    var mainPart = $($.ymPublish.settings.mainContent);
    mainPart.css('z-index','1');
    mainPart.fadeOut("slow");
    var showPart = $($.ymPublish.settings.partContent);
    showPart.css('z-index','999');
    showPart.css('left','-100%');
    showPart.show();
    showPage.animate({ left:'0'});
};
jQuery.ymPublish._loadPickList = function(listName, fieldName){};
