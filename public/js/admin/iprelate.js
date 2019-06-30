function deleteItem(id) {
    doConfirm('确认删除', '你确定要删除这条记录吗?',id, function(recordId){
        $.post('/admin/ip/delete-'+objName+'/',{'id':recordId,  "_token":token},
            function(data){
                $('#row_'+data).remove();
            }).error(function(a,b,c){
                doAlert('删除失败。');
            });

    });
}
function modifyTextItem(id) {
    $.get('/admin/ip/edit-'+objName+'/'+id,function(data){
        for(var i = 0;i<paramsArr.length; i++){
            paramsArr[i].value = data[paramsArr[i].name];
        }
        doPrompt('编辑',paramsArr,function(params){
            var postData = {'_token':token};
            for(var i = 0;i<params.length;i++){
                var v = params[i];
                postData[v.name] = v.value;
            }
            $.post('/admin/ip/edit-'+objName+'',postData,function(data){
                    window.location.reload();
                }).error(function(a,b,c){
                    doAlert("编辑失败。");
                });
        });
    });
}
function approveItem(id){
  var postData = {'_token':token, 'ids':id};
  $.post('/admin/ck/approve/'+objName,postData,function(data){
          window.location.reload();
      }).error(function(a,b,c){
          doAlert("操作失败。");
      });
}
function rejectItem(id){
  var postData = {'_token':token, 'ids':id};
  $.post('/admin/ck/reject/'+objName,postData,function(data){
          window.location.reload();
      }).error(function(a,b,c){
          doAlert("操作失败。");
      });
}

