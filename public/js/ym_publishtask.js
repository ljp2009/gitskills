
function showEditMainPage(noAnimate){
    var actPage = getActivityPage();
    actPage.css('z-index','1');
    actPage.fadeOut("slow");
    var showPage = $("#mainPage");
    showPage.css('z-index','899');
    if(typeof(noAnimate) != 'undefined' && noAnimate){
      showPage.css('left','0');
      showPage.show();
    }
    else{
      showPage.css('left','-100%');
      showPage.show();
      showPage.animate({ left:'0'});
    }
}
function showEditPartview(partName,id,loadFunc){
    var actPage = $("#mainPage");//getActivityPage();
    actPage.css('z-index','1');
    actPage.fadeOut("slow");
    var showPage = $("#editPage");
    showPage.css('z-index','899');
    showPage.css('right','-100%');
    showPage.show();
    var lfType = typeof(loadFunc);
    switch(lfType){
      case 'function':
        loadFunc(partName,id);
        break;
      case 'string':
        loadEditPartview(partName, id, loadFunc);
        break;
      default:
        loadEditPartview(partName, id);
        break;
    }
    showPage.animate({ right:'0'});
}
function showChildEditPage(pageName, id){
  if(typeof(id) == 'undefined') id = getId();
  window.location = '/pubtask/manage-'+pageName+'/'+id;
}
function showBottomMenu(menu){
    var $shade = $('#shade');
    $shade.css('z-index','998');
    $shade.css('left','0');
    $shade.css('background-color', 'rgba(0,0,0,0.5)');
    $shade.show();
    var $menu = $(menu);
    $menu.css('z-index','999');
    $menu.css('left','0');
    $menu.css('height','280px');
    $menu.css('bottom','-100%');
    $menu.show();
    $menu.animate({bottom:'0'},'fast');
}
function hideBottomMenu(menu){
    var $shade = $('#shade');
    $shade.hide();
    var $menu = $(menu);
    $menu.hide();
}
function loadEditPartview(partName, id, url){
    if(typeof(id) == 'undefined') id = getId();
    var getUrl = '';
    if(typeof(url) != 'undefined'){
      getUrl = url;
    }else{
      getUrl = '/pubtask/load-'+partName+'/'+id;
    }
    $.get(getUrl, function(partview){
      var editPage = $("#editPage");
      editPage.html(partview);
      if(getId()=='0'){
        loadNowValue();
      }
    }).error(function(e){
        alert(e);
    });
}
function gotoChildPage(pageName){
        window.location = '/pubtask/gotoedit/'+pageName+'/'+getId();
}
function getActivityPage(){
    return $(".ym_taskmg_page:visible");
}
function getId(){
    return $('#taskId').val();
}
function getToken(){
  return $('meta[name="csrf-token"]').attr('content');
}
function backToMainEditPage(){
  window.location = '/pubtask/manage-main/'+getId();
}
function backToTaskHall(){
  window.location = '/taskhall';
}
function backToHistory(){
  history.go(-1);
}
function showError(text){
    if(text.length > 0){
        $('.ym_taskmg_error').text(text);
        $('.ym_taskmg_error').show();
    }else{
        $('.ym_taskmg_error').text('');
        $('.ym_taskmg_error').hide();
    }
}
function back(){
    if(!$("#mainPage").is(':visible')){
        showEditMainPage();
    }
}
function finish(value, partName){
    if(validate(value, partName)){
      var fun = makeSaveParamCallBackFunction(partName,value);
      fun();
    }
}
function save(value, partName){
    if(validate(value, partName)){
      var saveValue = value;
      if(typeof(value)=='object'){
        saveValue = value.key;
      }
      saveTaskParemater(partName, saveValue,
            makeSaveParamCallBackFunction(partName,value));
    }
}
function validate(value, partName){
    if(partName == 'title' && value == ''){
        showError('任务名称不能为空。');
        return false;
    }
    return true;
}
function saveTaskParemater(name, value, afterSave){
    var url = '/pubtask/saveparam';
    $.post(url, {
        'taskId':getId(),
        'paramName':name,
        'value':value,
        '_token': getToken()
    }, function(v){
       if(v.res == true){
           afterSave();
       }else{
           showError(v.info);
       }
    }).error(function(e){
        alert(e);
    });
}
function makeSaveParamCallBackFunction(name, value){
    if(typeof(value) == 'object'){
      return function(){
          $('#ym_param_'+name+'_show').text(value.value);
          $('#ym_param_'+name+'_value').val(value.key);
          back();
      };
    }else{
      return function(){
          $('#ym_param_'+name+'_show').text(value);
          back();
      };
    }
}
function previewTask(id){
  window.location = "/task/"+getId();
}
