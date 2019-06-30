jQuery.ymPop = { shade:null, form:null};
jQuery.ymPop.alert=function(text){}
jQuery.ymPop.confirm=function(text){}
jQuery.ymPop.input=function(text){}
jQuery.ymPop.date=function(text){}
jQuery.ymPop.select=function(text){}
jQuery.ymPop.number=function(text){}
jQuery.ymPop.showForm=function(){
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
jQuery.ymPop.hideForm=function(){
}

