function skillHandler(){
  var settings = {
    'container':'',
    'addBtn':'',
  };
  var funcs = {};
  funcs.init = function(){
    funcs.loadItems();
  };
  funcs.loadItems = function(){
    $.get('/uset/loadskill', function(data){
      if(data.res){
        var ct=data.info.length;
        for(var i=0; i<ct; i++){
          funcs.addItem(data.info[i]);
        }
      }
    });
  };
  funcs.addItem = function(item){
    var itemRow = $('<div class="ym_skill_row" code="'+item.code+'" level="'item.level'"></div>');
    var itemLabel = $('<label>'+item.name+'</label>');
    var switchBtn = $('<span>'+funcs.getLevel(item.level)+'</span>');
    var rmBtn = $('<i></i>');
    switchBtn.on('click', function(){});
    rmBtn.on('click', function(){});
    itemRow.append(itemLabel);
    itemRow.append(switchBtn);
    itemRow.append(rmBtn);
    $(settings.container).append(itemRow);
  };
  funcs.switchLevel = function(){
  };
  funcs.removeItem = function(){
  };
  this.init = function(){
    funcs.init();
  };
  funcs.getLevelLabel = function(level){
    var mp = ['爱好','达人','专业','专家','大神'];
    return mp[level-1];
  };
}
