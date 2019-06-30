
function doAlert(str) {
    var atControl = $("#my-alert");
    atControl.find('div[class=am-modal-hd]').html('提示');
    atControl.find('div[class=am-modal-bd]').html(str);
    atControl.modal('open');
}
function doConfirm(title,text,params,func){
    var cfControl = $("#my-confirm");
    cfControl. find('div[class=am-modal-hd]').html(title);
    cfControl.find('div[class=am-modal-bd]').html(text);
    var relatedTarget = {
      params : params,
      func : func
    };
    cfControl.modal({
        relatedTarget:relatedTarget,
        onConfirm:function(){
          this.relatedTarget.func(this.relatedTarget.params);
        }
    });
}
function doPrompt(title,controls, func){
    var prControl = $("#my-prompt");
    prControl.find('div[class=am-modal-hd]').html(title);
    var dbControl = prControl.find('div[class=am-modal-bd]');
    dbControl.html('');
     for (var i = 0, l =controls.length; i < l; i++) {
       var v = controls[i];
       if(v.type == 'text'){
         dbControl.append('<input name="'+v.name+'" class="am-modal-prompt-input" type="text" placeholder="'+v.text+'" value="'+v.value+'"/>');
       }
       if(v.type == 'textarea'){
         dbControl.append('<textarea name="'+v.name+'" class="am-modal-prompt-input" rows="5" placeholder="'+v.text+'">'+v.value+'</textarea>');
       }
       if(v.type == 'enum'){
         var em = $('<select name="'+v.name+'" class="am-modal-prompt-input"></select>');
         for(var opi = 0; opi<v.options.length; opi++){
           var ss = (v.options[opi].value == v.value?'selected="selected"':'');
           em.append('<option value ="'+v.options[opi].value+'" '+ss+'>'+v.options[opi].name+'</option>');
         }
         dbControl.append(em);
       }
       if(v.type == 'hidden'){
         dbControl.append('<input name="'+v.name+'" type="text" class="am-modal-prompt-input" style="display:none" value="'+v.value+'"/>');
       }
    }
    prControl.modal({
        relatedTarget:controls,
        onConfirm:function(d){
            for (var i = 0, l = this.relatedTarget.length; i < l; i++) {
              this.relatedTarget[i].value = d.data[i];
            }
            func(this.relatedTarget);
        }
    });
}
function formatPager(pager){
    pager.find('ul').addClass('am-pagination');
    pager.find('ul').addClass('admin-content-pagination');
    pager.find('li[class=disabled]').addClass('am-disabled');
    pager.find('li[class=active]').addClass('am-active');
}
function adminDeleteItem(id, resource){
    doConfirm('确认删除','你确定要删除吗？',id, function(recordId){
        $.post('/admin/ck/delete/'+resource,{'ids':recordId,  "_token":"{{ csrf_token() }}"},
         function(data){
             if(data.res){
                 $('#row_'+data.info).remove();
             }
             else{
                 doAlert('删除失败。');
             }
         }).error(function(a,b,c){
             doAlert('删除失败。');
         });
    });
}
function adminApproveItem(id, resource){
    doConfirm('审核确认','你确定要通过吗？',id, function(recordId){
        $.post('/admin/ck/approve/'+resource,{'ids':recordId,  "_token":"{{ csrf_token() }}"},
         function(data){
             if(data.res){
                 $($('#row_'+data.info).find('td')[4]).html('已通过');
             }else{
                 doAlert('操作失败。');
             }
         }).error(function(a,b,c){
             doAlert('操作失败。');
         });
    });
}
function adminRejectItem(id, resource){
    doConfirm('审核确认','你确定要禁用吗？',id, function(recordId){
        $.post('/admin/ck/reject/'+resource,{'ids':recordId,  "_token":"{{ csrf_token() }}"},
         function(data){
             if(data.res){
                 $($('#row_'+data.info).find('td')[4]).html('已拒绝');
             }else{
                 doAlert('操作失败。');
             }
         }).error(function(a,b,c){
             doAlert('操作失败。');
         });
    });
}
