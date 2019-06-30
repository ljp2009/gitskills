function signInHandler(){
  var settings = {};
  var funcs = {};
  funcs.showBtn = function(){
    $('#sign_in_item').on('click', function(){
      funcs.sign();
    });
    $('#sign_in_item').append('<span class="ym_sidebar_alert_flag"></span>');
  };
  funcs.check = function(){
    $.get('/signin/check', function(data){
      if(data.res && !data.info){
        funcs.showBtn();
      }else{
        $('#sign_in_item').html('<i class="ymicon-t-finish"></i>&nbsp;&nbsp;已签到');
      }
    });
  };
  funcs.sign = function(){
    $.post( '/signin/sign',
      { '_token':$.ymFunc.getToken() },
      function(data){
        $.ymSideBar.hide();
        $.ymShade.hide();
        if(data.res){
          funcs.showResult(data.info);
        }else{
        }
    });
  };
  funcs.showResult = function(data){
    var shade = $('<div class="ym_pop_shade"></div>');
    $('body').append(shade);
    shade.css('line-height', shade.height()+'px');
    shade.show();
    shade.on('click', function(){
      shade.remove();
    });
    var signForm = $('<div class="ym_sign_page"></div>');
    shade.append(signForm); 
    var height = signForm.width(); 
    signForm.css('height', signForm.width()+'px');
    var above =$('<div class="above">签到成功</div>');
    signForm.append(above);
    above.css('line-height', (signForm.width()/3)+'px');

    var days = data.days;
    for(var i=0;i<4;i++) {
      var pointDays = 2*i+1;
      funcs.showPoint(signForm, 2*i+1, pointDays<=days, height/2);
    } 
    var info = $('<div class="info_show"></div>');
    signForm.append(info);
    info.html('您已经连续签到<b>'+days+'</b>天<br/>今日签到获得<b>'+data.coins+'</b>金币');
    //显示已经连续多久
    //显示本次回报
    //显示再连续多久可以汇报提升（或者已经最大值，继续保持）
  };
  funcs.showPoint =  function (signForm, index, status, height){
    var point =$('<div class="point"></div>');
    point.css('margin-top', height+'px');
    signForm.append(point);
    point.append('<label class="label">第'+index+'天</label>');
    if(status){
      point.append('<span class="icon selected"></icon>');
    }else{
      point.append('<span class="icon"></icon>');
    }
  };
  this.check = function(){
    funcs.check();
  };
}
$(document).ready(function(){
  var sih = new signInHandler();
  sih.check();
});
