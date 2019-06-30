function voteCtrl(){
  var $voteCtrl = this;
  var settings = {};
  var objs = {};
  var funcs = {};
  funcs.init = function(){
    objs.shade = $('<div class="ym_pop_shade"></div>');
    objs.position = $('<div class="ym_pop"></div>');
    $('body').append(objs.shade);
    $('body').append(objs.position);
    $('body').css('overflow','hidden');

    objs.form = $('<div class="ym_vote_form"></div>');
    objs.position.append(objs.form);
    objs.header = $('<div class="ym_vote_header"></div>');
    objs.form.append(objs.header);
    objs.body = $('<div class="ym_vote_body"></div>'); 
    objs.form.append(objs.body);
    objs.footer = $('<div class="ym_vote_footer"></div>'); 
    objs.form.append(objs.footer);
    objs.btn1 =  $('<button type="button" vid="false"></button>'); 
    objs.btn2 =  $('<button type="button" vid="true"></button>'); 
    objs.footer.append(objs.btn1);
    objs.footer.append(objs.btn2);

    objs.img = $('<img src="http://img.umeiii.com/default.jpg@100h_80w_1e_1c.jpg">');
    objs.header.append(objs.img);
    objs.label = $('<label>说明</label>');
    objs.header.append(objs.label);
    objs.shade.show();

  };
  funcs.bindIntro = function(intro){
    objs.label.html(intro.label);
    objs.img.attr('src', intro.img);
  };
  funcs.bindItems = function(type, items){
    var tarDom = '';
    if(type == 'image'){
      funcs.bindImgItems(items);
      tarDom = 'img';
    }else if(type == 'text'){
      funcs.bindTextItems(items);
      tarDom = 'pre';
    } 
    if(items.length > 1){
      objs.body.find('div[class *= ym_vote_item]').find(tarDom).on('click', function(){
        funcs.switchSelect($(this).parent());
      });
      objs.body.find('.ymicon-heart').on('click', function(){
        funcs.switchSelect($(this).parent());
      });
      objs.body.find('.ymicon-t-list').on('click', function(){
        funcs.preview($(this).parent()); 
      });
    }else{
      objs.body.find('div[class *= ym_vote_item]').addClass('ym_selected_1');
    }
  };
  funcs.bindImgItems = function(items){
    var model = '1';
    var itemCt = items.length; 
    if(itemCt == 1){
      model = '1';
    } else if(itemCt > 1 && itemCt < 5){
      model = '4';
    }else{
      model= '6';
    }
    var itemCls = ('ym_vote_item_image_'+model);
    for(var i=0;i<items.length;i++){
      var $item = $('<div class="'+itemCls+'" vid="'+items[i].id+'"></div>');
      $item.append('<img src="'+items[i].img+'" />');
      if(itemCt == 1){
        $item.append('<label>'+items[i].label+'</label>');
      }else{
        $item.append('<i class="ymicon-heart"></i>');
        $item.append('<i class="ymicon-t-list"></i>');
      }
      objs.body.append($item);
    }

  };
  funcs.bindTextItems = function(items){
    var model = items.length;
    var itemCt = items.length; 
    model = model>3?3:model;
    var itemCls = ('ym_vote_item_text_'+model);
    for(var i=0;i<items.length;i++){
      var $item = $('<div class="'+itemCls+'" vid="'+items[i].id+'"></div>');
        $item.append('<pre>'+items[i].text+'"</pre>');
      if(itemCt >1 ){
        $item.append('<i class="ymicon-heart"></i>');
        $item.append('<i class="ymicon-t-list"></i>');
      }
      objs.body.append($item);
    }
  };
  funcs.bindButton = function(ct){
    if(ct == 1){
      objs.btn1.text('不喜欢');
      objs.btn2.text('喜欢');
    }else{
      objs.btn1.text('都不喜欢');
      objs.btn2.text('选好了');
    }
    objs.footer.find('button').on('click',function(){
      var voteId = settings.vote.id;
      var action = $(this).attr('vid');
      var itemValues = [];
      if(action == 'true'){
        var items = objs.body .find('div[class *= ym_vote_item][class *= ym_selected]');
        items.each(function(index){
          itemValues.push($(this).attr('vid'));
        });
      }
      $.post('/vote/post',{
        '_token':$.ymFunc.getToken(),
        'voteId':voteId,
        'values':itemValues
      }, function(data){
        funcs.hide();
      });
    });
  };
  funcs.resizeForm = function(type, items){
    var w_width  = objs.shade.width();
    var w_height = objs.shade.height();
    var f_width = w_width - 20;
    var f_height = f_width+120+40; 
    var itemCt = items.length;
    
    if(type=='image' && itemCt == 2){
      var f_height = f_width/2+120+40; 
    }
    else if(type=='image' && itemCt > 4){
      var f_height = f_width*2/3+120+40; 
    }
    else if(type=='text' && itemCt == 1){
      var f_height = f_width/2+120+40; 
    }
    else if(type=='text' && itemCt == 2){
      var f_height = f_width*2/3+120+40; 
    }
    objs.form.css('width'  , f_width);
    objs.form.css('height' , f_height);
    objs.form.css('top'    , -f_height/2);
    objs.form.css('left'   , -f_width/2);
  };
  funcs.switchSelect = function(item){
      if($(item).hasClass('ym_selected')){
        $(item).removeClass('ym_selected');
      }else{
        $(item).addClass('ym_selected');
      }
  };
  funcs.preview = function(item){
    if(typeof(objs.preview) == 'undefined'){
      objs.preview = {};
      objs.preview.body = $('<div class="ym_vote_preview"></div>');
      objs.preview.close = $('<i class="ymicon-close"></i>');
      objs.preview.close.on('click', function(){
        objs.preview.body.hide();
      });
      objs.preview.body.append(objs.preview.close);
      if(settings.vote.type == 'image'){
        objs.preview.img = $('<img />');
        objs.preview.label = $('<label></label>');
        objs.preview.body.append(objs.preview.img);
        objs.preview.body.append(objs.preview.label);
      }
      else if(settings.vote.type == 'text'){
        objs.preview.pre = $('<pre></pre>');
        objs.preview.body.append(objs.preview.pre);
      }
      objs.form.append(objs.preview.body);
    }
    var vid = $(item).attr('vid');
    for(var i=0;i<settings.vote.items.length;i++){
      var tmp = settings.vote.items[i];
      if(tmp.id != vid){
        continue;
      }
      if(settings.vote.type == 'image'){
        objs.preview.img.attr('src', tmp.img);
        objs.preview.label.text(tmp.label);
      }
      else if(settings.vote.type == 'text'){
        objs.preview.pre.text(tmp.text);
      }
    }
    objs.preview.body.show();
  }
  funcs.show = function(vote){
    settings.vote = vote;
    funcs.init();
    funcs.bindIntro(vote.intro);
    funcs.resizeForm(vote.type, vote.items);
    funcs.bindItems(vote.type, vote.items);
    funcs.bindButton(vote.items.length);
    objs.position.show();
  };
  funcs.hide = function(){
    objs.position.remove();
    objs.shade.remove();
    $('body').css('overflow','auto');
  }
  funcs.check = function(){
    $.post('/vote/check', {
      '_token':$.ymFunc.getToken()
    }, function(data){
      if(data.res){
        funcs.show(data.vote);
      }
    });
  };

  this.check = function(vote){
    setTimeout(function(){
      funcs.check();
    }, 2000);
  };
  this.preview = function(vote){
    funcs.show(vote);
  };
}
