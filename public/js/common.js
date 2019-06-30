//comon status
jQuery.ymStatus ={};
//common function
jQuery.ymFunc = {};
jQuery.ymFunc.goTo = function(url){
  window.location = url;
};
jQuery.ymFunc.goHome = function(){
  $.ymFunc.goTo('/reshall');
};
jQuery.ymFunc.goLogin = function(){
  $.ymFunc.goTo('/auth/login');
};
jQuery.ymFunc.getToken = function(){
  return $('meta[name="csrf-token"]').attr('content');
};
jQuery.ymFunc.switchLike = function(resourceName, resourceId, afterLike){
  $.post('/common/switchlike',{
    '_token':$.ymFunc.getToken(),
		'resource':resourceName,
    'resourceId':resourceId
  },function(data){
    if(data.res){
      if(typeof(afterLike) == "function"){
        afterLike(resourceName, resourceId, data.isLike);
      }
      if(typeof(afterLike) == "object"){
        var $obj = $(afterLike);
        if($obj.hasClass('ymicon-heart')){
          $(afterLike).removeClass('ymicon-heart');
          $(afterLike).addClass('ymicon-heart-o');
        }
        else if($obj.hasClass('ymicon-heart-o')){
          $(afterLike).removeClass('ymicon-heart-o');
          $(afterLike).addClass('ymicon-heart');
        }
      }
    }else{
      $.ymFunc.goLogin(0);
    }
  }).error(function(e){
    alert($(e.responseText).find('#sf-resetcontent').html());
  });
};
jQuery.ymFunc.back = function(url){
    if(typeof(url) == 'undefined' || url==''){
        history.go(-1);
    }
    else{
        $.ymFunc.goTo(url);
    }
};
jQuery.ymFunc.setTitle = function(title){
  $('.ym_headerbar_title').text(title);
};
jQuery.ymFunc.checkWechat = function(){
    return /micromessenger/.test(navigator.userAgent.toLowerCase());
};
jQuery.ymFunc.showLoading = function(text){
    $('.ym_ajax_loading').remove();
    var $shade = $('<div class="ym_ajax_loading"><div><span>'+text+'</span></div></div>');
    $('body').append($shade);
    $shade.show();
};
jQuery.ymFunc.changeLoading = function(text){
    $('.ym_ajax_loading').find('div').find('span').text(text);
};
jQuery.ymFunc.hideLoading = function(text){
    setTimeout(function(){
    $('.ym_ajax_loading').remove();
    }, 1000);
};
jQuery.ymFunc.showVote = function(){
    var $voteBox = $('#vote_box');
    $voteBox.show();
    var imgs = $voteBox.find('img');
    var readyCt = 0;
    imgs.load(function(obj){
      readyCt++;
      if(readyCt == imgs.length){
        $voteBox.show();
        $box.css('top', 20-$box.height()/2);
      }
    });
};
//headerbar function
jQuery.ymHeaderBar = {};
jQuery.ymHeaderBar.hideIcon = function(showClose){
    $('.ym_headerbar_left').find('i').hide();
    $('.ym_headerbar_right').find('i').hide();
    if(showClose){
      $('.ym_headerbar_right').find('i.ymicon-close').show();
    }
};
jQuery.ymHeaderBar.showIcon = function(){
    $('.ym_headerbar_left').find('i').show();
    $('.ym_headerbar_right').find('i').show();
    $('.ym_headerbar_right').find('i.ymicon-close').hide();
};
//shade function
jQuery.ymShade = {};
jQuery.ymShade.show = function(level, activeBarFunc){
  $.ymStatus.activeBarFunc = activeBarFunc;
  var $shade = $('.ym_shade');
  if(level == 'full'){
    $shade.css('z-index','800');
  }
  if(level == 'half'){
    $shade.css('z-index','500');
  }
  $shade.show();
  $('body').css('overflow','hidden');
};
jQuery.ymShade.hide = function(){
  $.ymStatus.activeBarFunc();
  $.ymHeaderBar.showIcon();
  $('.ym_shade').hide();
  $('body').css('overflow-y','auto');
};

//sidebar function
jQuery.ymSideBar = {};
jQuery.ymSideBar.show = function(){
  var $sideBar = $('.ym_sidebar');
  $.ymShade.show('full',function(){
    $.ymSideBar.hide();
  });
  $sideBar.css('z-index','900');
  $sideBar.css('left','-100%');
  $sideBar.show();
  $sideBar.animate({ left:'0'});
};
jQuery.ymSideBar.hide = function(){
    $('.ym_sidebar').hide();
};
jQuery.ymSideBar.itemOnClick = function(item, url){
    $(item).css('background-color', '#f5f5f5');
    $.ymFunc.goTo(url);
};
//searchBar function
jQuery.ymSearchBar = {};
jQuery.ymSearchBar.show = function(){
  var $searchBar = $('.ym_searchbar');
  $.ymShade.show('half',function(){
    $.ymSearchBar.hide();
    $.ymHeaderBar.showIcon();
  });
  $.ymHeaderBar.hideIcon(true);
  $searchBar.css('z-index','600');
  $searchBar.slideDown('fast');
};
jQuery.ymSearchBar.hide = function(){
    $('.ym_searchbar').hide();
};
jQuery.ymSearchBar.search = function(){
  var $input = $('.ym_searchbar_input');
  if($input.val().length == 0){
    $input.addClass('ym_err_empty_input');
    return false;
  }
  return true;
};
// commentbar function
jQuery.ymCommentBar = {};
jQuery.ymCommentBar.submit = function(){
  var resource = $.trim($('#commentbar_resource').val());
  var resourceId =$.trim($('#commentbar_resource_id').val());
  var text =$.trim($('#commentbar_text').text());
  if(resource == '' || resourceId=='' || resourceId=='0' || text == ''){
    return;
  }
  if(text.length > $('#commentbar_text').attr('maxlength')){
    text = text.substring(0,$('#commentbar_text').attr('maxlength'));
  }
  $.post('/discussion/publish', {
    '_token':$('meta[name="csrf-token"]').attr('content'),
    'resource':resource,
    'resourceId':resourceId,
    'text':text
  }, function(data){
      if(data.res){
        location.reload();
      }
  }).error(function(e){
    alert(e.responseText);
  });
};
$(document).ready(function(){
  if($('.ym_commentbar').length == 0) return;
  $('#commentbar_text').keydown(function(e){
    if(e.which != 13) return;
    $.ymCommentBar.submit(e)
  });
  $.post('/discussion/count',{
    '_token':$('meta[name="csrf-token"]').attr('content'),
    'resource':$('#commentbar_resource').val(),
    'resourceId':$('#commentbar_resource_id').val()
  }, function(data){
    if(data.res)
      $('#ym_commentbar_count').text(data.info);
    else
      $('#ym_commentbar_count').text(0);
  });
});

//footerbar function
jQuery.ymFooterBar = {};
//addpanel function
jQuery.ymAddPanel = {};
jQuery.ymAddPanel.show = function(panel){
  if(typeof(panel) == 'undefined' || panel==''){
    panel = '#ym_add_panel';
  }
  $(panel).modal('open');
};

//图片放大查看
jQuery.ymImgShow = {};
jQuery.ymImgShow.show = function(img, actions){
  var $preview = $('.ym_cm_preview'); 
  var $shade = $('.ym_cm_preview_shade');
  var $container = $('.ym_cm_preview_container');
  var $image = $('.ym_cm_preview_image');
  var $title = $('.ym_cm_preview_title');
  var $label = $('.ym_cm_preview_label');
  $shade.on('click', function(){
    $preview.hide();
  });
  $preview.show();
  $image.on('load', function(){
    $container.css('width', $shade.width()-20);
    $container.css('max-height', $shade.height()-100);
    $container.css('left',-($container.width()+20)/2);
    $container.css('top', -($container.height()+20)/2);
  });
  var labelText = $(img).attr('text');
  var labelTitle = $(img).attr('title');
  if(labelText != null && labelText != ''){
    $label.html('<b>'+labelTitle+'：</b>'+labelText);
    $label.show();
  }else{
    $label.hide();
  }
  if(typeof(actions) != 'undefined' && $('.ym_cm_preview_actions').length ==0){
    var $actionBox =$('<ul class="ym_cm_preview_actions"></ul>'); 
    var btCt = actions.length;
    $actionBox.addClass('ym_avg_'+btCt);
    $container.append($actionBox);
    for(var i=0;i<btCt;i++){
      var $li = $('<li>'+actions[i].name+'</li>');
      $actionBox.append($li);
      $li.on('click', actions[i].func(img));
    }
  }
  $image.attr('src', $(img).attr('origin'));
};

jQuery.ymImgShow.bind = function(img, actions){
  $(img).on('click', function(e){
    $.ymImgShow.show(this, actions);
    e.stopPropagation(); 
  });
} 
//validate common function
jQuery.ymValidator = {};
jQuery.ymValidator.checkPhone = function(value){
  return value.match(/^1[3|4|5|8][0-9]\d{4,8}$/);
};
jQuery.ymValidator.checkEmail = function(value){
  return value.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/);
};
//弹出居中菜单
jQuery.ymPopMenu = {};
jQuery.ymPopMenu.bind = function(uopts){
  var settings = {
    'popBtn':'.ym_footerbar_addbtn',
    'menus':[]
  };
  var objs = {};
  var funcs = {};
  funcs.init = function(){
    if(typeof(uopts) == 'object'){
      for(var key in uopts){
        settings[key] = uopts[key];
      }
    }
    $(settings.popBtn).unbind('click');
    $(settings.popBtn).bind('click', funcs.showPopMenu);
  };
  funcs.getMenuObj = function(){
    if(typeof(objs.shade) == 'undefined'){
      objs.shade = $('<div class="ym_pop_shade"></div>');
      objs.position = $('<div class="ym_pop"></div>');
      objs.menu = $('<div class="ym_pop_menu"></div>');
      objs.position.append(objs.menu);
      for(var i=0; i<settings.menus.length; i++){
        var muSet = settings.menus[i];
        var item = $('<div class="ym_pop_menu_item"></div>');
        itemBtn = $('<a></a>');
        itemBtn.html(muSet.text);
        itemBtn.attr('href', muSet.url);
        item.append(itemBtn);
        if(typeof(muSet.help) != 'undefined'){
          itemBtn.addClass('ym_half');
          var hpBtn = $('<span class="ym_help" href="'+muSet.help+'">说明</span>')
          hpBtn.on('click', function(){
            $.ymFunc.goTo($(this).attr('href'));
          });
          item.append(hpBtn);
        }
        objs.menu.append(item);
      }
      objs.shade.on('click', function(){
        funcs.hidePopMenu();
      });
      $('body').append(objs.shade);
      $('body').append(objs.position);
    }
    return objs;
  };
  funcs.showPopMenu = function(){
      var menuObj = funcs.getMenuObj();
      menuObj.shade.show();
      menuObj.menu.show();
      menuObj.menu.css('width', menuObj.shade.width()*0.8);
      menuObj.menu.css('left',1 - menuObj.menu.width()/2);
      menuObj.menu.css('top',1 - menuObj.menu.height()/2);
  };
  funcs.hidePopMenu = function(){
      var menuObj = funcs.getMenuObj();
      menuObj.shade.hide();
      menuObj.menu.hide();
  };
  //run Init function
  funcs.init();
};
//通知
jQuery.ymNotice = {};
jQuery.ymNotice.show = function(text, settings){
  var $shade = $('<div class="ym_pop_shade"></div>');
  var $form = $('<div class="ym_notice_form">'+text+'</div>');
  $('body').append($shade);
  $shade.css('line-height', $shade.height()+'px');
  $shade.append($form);
  $shade.on('click', function(){$shade.remove();});
  setTimeout(function(){$shade.remove();}, 4000);
};

//编辑框
(function($){$.extend($.fn, { ymEditField:function(uopts){
  var setting = {
    'title'      : uopts['title'],
    'valueField' : uopts['valueField'],
    'callback'   : uopts['callback'],
    'maxLength'  : uopts['maxLength'],
    'feature'    : '.ym_tmp_editfield'
  };
  var status = {};
  var funcs = {};
  funcs.showShade = function(){
    status.shade = $('<div class="ym_shade ym_tmp_editfield"></div>');
    status.content = $('<div class="ym_edit_field_box ym_tmp_editfield">'
        +'<input type="text" maxlength="'+setting.maxLength+'" class="input" '
        +'placeholder="'+setting.title+'" value="'+$(setting.valueField).text()+'" />'
        +'<button type="button" class="btn">更新</button>'
        +'<span class="err"></span>'
        +'</div>');
    $('body').append(status.shade);
    $('body').append(status.content);
    status.content.find('button').on('click', function(){
      var value = status.content.find('input').val();
      setting.callback(value, funcs.eventHandler);

    });
    status.shade.css('z-index', 1000);
    status.shade.show();
    status.content.show();
    status.shade.on('click', funcs.hideShade);
  };
  funcs.hideShade = function(){
    if(typeof(status.shade) != 'undefined'){
      $(setting.feature).remove();
    }
  };
  funcs.eventHandler = {
    finish:function(e){
      funcs.hideShade();
    },
    error:function(e){
      var errCtrl = status.content.find('.err');
      errCtrl.text(e);
      errCtrl.show();
    }
  };
  this.each(function(){ 
    $(this).on('click',function(){
      funcs.showShade();
    });
  });
} }); })(jQuery);
