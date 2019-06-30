function WLLoadControl(){
  this.setting ={
    'containers':['#work-container']
    //'containers':['#left-container','#right-container']
  };
  var obj =this;
  this.bind = function(obj){};
  this.appendItem = function(item){
     var shortContainer = null;
     for (var i = 0, l = this.setting.containers.length; i < l; i++) {
       var v =$(this.setting.containers[i]);
       if(i == 0) shortContainer = v;
       if(shortContainer.height() > v.height()) shortContainer = v;
     }
     shortContainer.append(this.makeItemDom(item));
  };
  this.makeItemDom = function(item){
    var str ='<div class="my-work-box ym_listitem" style="width:100%">'+
      '<div style="text-align:left">'+item.name+'</div>';
      if(item.imgUrl == ''){
      str += '<div style="width:100%;max-height:140px;overflow:hidden;text-align:left">'+item.text+'</div>';
      }
      else{
      str += '<div style="width:100%;max-height:140px;overflow:hidden"><img src="'+item.imgUrl+'" style="width:100%" /></div>';
      }
      str += '<div class="am-line"></div>'+
      '<div class="am-msg">'+
        '<i class="am-icon-heart"></i>'+item.likeCount+
        '<i class="am-icon-comment"></i>'+item.commentCount+
      '</div>'+
      '</div>';
      return str;
  };
}
var _WLLoadControl = new WLLoadControl();
