function userWizzardCtrl(){
  var settings = {
    's1':"http://img.umeiii.com/dimpub/def-1491980257497-.jpg@.png",
    's2':"http://img.umeiii.com/dimpub/def-1491980261668-.jpg@.png",
    's3':"http://img.umeiii.com/dimpub/def-1491980265798-.jpg@.png",
    'shade':null,
    'step':1
  };
  var funcs = {};
  funcs.init = function(){
    settings.shade = $('<div class="ym_pop_shade"></div>');
    $('body').append(settings.shade);
    settings.shade.append('<img id="uw_01" class="ym_user_wizzard" src="'+settings.s1+'">');
    settings.shade.append('<img id="uw_02" class="ym_user_wizzard" src="'+settings.s2+'">');
    settings.shade.append('<img id="uw_03" class="ym_user_wizzard" src="'+settings.s3+'">');
  };
  funcs.showValue = function(){
      for(var i=1;i<=3;i++){
        if(i == settings.step){
          $('#uw_0'+i).show();
        }else{
          $('#uw_0'+i).hide();
        }
      }
  }
  this.show = function(){
    settings.shade.on('click', function(){
      funcs.showValue();
      settings.step += 1;
      if(settings.step > 4){
        settings.shade.remove();
      }
    });
    funcs.showValue();
    settings.step += 1;
  }
  funcs.init();
}
$(document).ready(function(){
  var uwd = new userWizzardCtrl();
  uwd.show();
});
