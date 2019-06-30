(function($){
  $.extend($.fn, {
    //查询IP
    searchCombo:function(uopts){
      var obj =this;
      var settings = {
        value : {id:0, name:'', url:''},
        buffer:0,
        isChange:false};
      var funcs = {};
      funcs.init = function(){
        for(var key in uopts){
          settings[key] = uopts[key];
        }
        obj.on('input', function(){
          funcs.casheSearch();
        });
        obj.on('click', function(){
          funcs.showList(settings.value);
        });
        obj.on('blur', function(){
          setTimeout(function(){
            obj.setValue(settings.value);
            funcs.hideList();
          }, 300);
        });
      };
      funcs.casheSearch = function(){
        settings.buffer = 0; 
        if(!settings.isChange){
          settings.isChange = true;
          var timeInterval = setInterval(function(){
            settings.buffer ++;
            if(settings.buffer > 10){
              settings.isChange =false;
              var keyword = obj.val();
              console.log(keyword);
              var reslist = funcs.showList(keyword);
              funcs.search(keyword, reslist);
              window.clearInterval(timeInterval);
            }
          }, 100);
        }
      }
      funcs.search = function(keyword, resCtrl){
        setTimeout(function(){
          if(keyword == ''){
          resCtrl.text('请输入查询关键字。');
          }else{
            $.post('/pub/quick-search',{
                '_token':$('meta[name="csrf-token"]').attr('content'),
                'keywd':keyword
                },function(ips){
                  resCtrl.html('');
                for(var i=0; i< ips.length;i++){
                  funcs.appendSearchRes({ id:ips[i].id, name:ips[i].name, cover:ips[i].cover}, resCtrl);
                }
            }).error(function(a){
                alert(a);
            });
            var x = 10 - keyword.length;
            if(x<=0) x = 1;
            for(var i=0; i<x; i++){
            }
          }
        }, 1000);
      };
      funcs.showList = function(keyword){
        var resList =null;
        if($('.ym_fp_search_res').length>0){
          resList = $('.ym_fp_search_res');
        }else{
          resList = $('<div class="ym_fp_search_res"><div class="result"></div></div>');
          var clearBtn  = $('<button type="button" class="clear_btn">清除</button>');
          var cancelBtn = $('<button type="button" class="cancel_btn">取消</button>');
          resList.insertAfter(obj);
          resList.append(clearBtn);
          resList.append(cancelBtn);
          var height = obj.height();
          var width = obj.width();
          resList.css('top', obj.position().bottom);
          resList.css('left', obj.position().left);
          resList.animate({height:'130px'}, 200);
          clearBtn.on('click', function(){
            obj.setValue({id:0, name:'', cover:''});
            funcs.hideList();
          });
          cancelBtn.on('click', function(){
            obj.setValue(settings.value);
            funcs.hideList();
          });
        }
        var resCtrl = resList.find('.result');
        if(typeof(keyword) != 'object' || keyword.id == 0){
          if(typeof(keyword) == 'undefined' || keyword == '' || keyword.id==0){
            resCtrl.text('请输入IP名称关键字');
          }else{
            resCtrl.text('查询"'+keyword+'"中……');
          }
        }else{
          resCtrl.html('');
          funcs.appendSearchRes(keyword, resCtrl);
        }
        return resCtrl;
      };
      funcs.hideList = function(){
          resList = $('.ym_fp_search_res');
          resList.remove();
      };
      funcs.appendSearchRes = function(data, resList){
        var item = $('<div ipid="'+data.id+'" class="ip_item"><img src="'+data.cover+'">'+data.name+'</div>');
        item.on('click', function(){
          obj.setValue(data);
          funcs.hideList();
        });
        resList.append(item);
      };
      this.setValue = function(value){
        settings.value = value;
        if(value.id == 0){
          obj.parent().find('img').remove();
          obj.css('padding-left', '5px');
          obj.val(value.name);
        }else{
          obj.parent().append('<img src="'+value.cover+'" />');
          obj.css('padding-left', '45px');
          obj.val(value.name);
        }
      };
      this.getValue = function(){
        return settings.value;
      };
      funcs.init();
      return obj;
    },

    //切换按钮
    switchBtn:function(uopts){
      var obj =this;
      var settings = {};
      var funcs = {};
      funcs.init = function(){
        settings.items = uopts.items;
        obj.setValue(uopts.value);
        obj.on('click', function(){
          var i = funcs.findIndex(settings.value);
          if(i < settings.items.length - 1){ i++; }
          else{ i = 0;}
          obj.setValue(settings.items[i].key);

        });
      };
      funcs.findIndex = function(key){
          var i = 0;
          for(i = 0; i< settings.items.length; i++){
            if(settings.items[i].key == settings.value){
              break;
            }
          }
          return i;
      };
      funcs.showValue = function(index){
        obj.text(settings.items[index].text);
        obj.append('<i class="ymicon-order"></i>');
      };
      this.setValue = function(value){
        settings.value = value;
        var index = funcs.findIndex(value);
        funcs.showValue(index);
      };
      this.getValue = function(){
        return settings.value;
      };
      funcs.init();
      return obj;
    },
  });
 })(jQuery);


