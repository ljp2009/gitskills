function enterDimension(id, afterPost){
  $.post('/dimension/enter-switch',{
    '_token':$('meta[name="csrf-token"]').attr('content'),
    'id':id,
    'status':status
    }, function(data){
      if(data.res){
        if(typeof(afterPost) == 'function'){
          afterPost(id, data.info);
        }
      }
    }).error(function(e){alert(e.responseText)});
}
function listAfterEnter(id, status){
  var $btn = $('#btn_dim_'+id);
  if(status == 'N'){
    $btn.removeClass('dimension-operation-disable');
    $btn.find('a').html('入驻次元');
  } else if(status=='Y'){
    $btn.addClass('dimension-operation-disable');
    $btn.find('a').html('已入驻');
  }
}
function infoAfterEnter(id, status){
  var $btn =$('.ym_dim_header_button');
  if(status == 'Y'){
    $btn.addClass('ym_active');
    $btn.find('span').html('已入驻');
  }else if(status == 'N'){
    $btn.removeClass('ym_active');
    $btn.find('span').html('入驻次元');
  }
}
