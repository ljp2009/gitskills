function getActionSet(action, taskId){
  var set = { };
  switch(action){
    case 'back':
      set = {'type':'back','url':''};
      break;
    case 'modify':
      set = {'type':'goto','url':'/pubtask/manage-main/'+taskId};
      break;
    case 'requestjoin':
      set = {'type':'confirm',
             'url':'/jointask/request',
             'data':{'taskid':taskId,'_token':$.ymFunc.getToken()},
             'title':'申请参与',
             'content':' 您确定要参与这个任务吗？'};
      break;
    case 'showrequests':
      set = {'type':'goto','url':'/jointask/list/request/0/'+taskId};
      break;
    case 'confirmjoin':
      set = {'type':'confirm',
             'url':'/jointask/confirm',
             'data':{'taskid':taskId,'_token':$.ymFunc.getToken()},
             'title':'确认参与',
             'content':' 发布者已经同意与您达成合作，在您确认后任务将进入交付阶段，请您确认前认真检查任务内容，您确定现在要参与任务吗？'};
      break;
    case 'invite':
      set = {'type':'menu' };
      break;
    case 'delivery':
      set = {'type':'goto','url':'/taskdelivery/'+taskId};
      break;
    case 'viewdelivery':
      set = {'type':'goto','url':'/taskdelivery/list/default/0/'+taskId};
      break;
    case 'requestcancel':
      set = {'type':'goto','url':'/task/requestcancel/'+taskId};
      break;
    case 'cancelstatus':
      set = {'type':'goto','url':'/task/showcancel/'+taskId};
      break;
    case 'finish':
      set = {'type':'goto','url':'/task/finish/'+taskId};
      /*set = {'type':'confirm',
             'url':'/task/finish',
             'data':{'taskid':taskId,'_token':$.ymFunc.getToken()},
             'title':'确认任务完成',
             'content':'任务完成后，乙方会收到任务的佣金，并且无法再提交交付物。你确定要这样做吗？'};
      */
      break;
    case 'login':
      set = {'type':'goto','url':'/auth/login/'};
      break;
    case 'communicate':
      set = {'type':'goto','url':'/task/viewcancel/'+taskId};
      break;
  }
  return set;
}

$(document).ready(function(){
  $("#ctrlBar").find('a').on('click', function(){
    var action = $(this).attr('action');
    var taskId = $("#ctrlBar").attr('taskId');
    var set = getActionSet(action, taskId);
    switch(set.type) {
      case 'back':
        $.ymFunc.back();
        break;
      case 'menu':
        $.ymAddPanel.show();
        break;
      case 'goto':
        $.ymFunc.goTo(set.url);
        break;
      case 'confirm':
        var cf = $("#my-confirm");
        cf.find('div[class="am-modal-hd"]').html(set.title);
        cf.find('div[class="am-modal-bd"]').html(set.content);
        cf.modal({
          relatedTarget:set,
          onConfirm:function(options){
                var myset = this.relatedTarget;
                var url = myset.url;
                var postData = myset.data;
                $.post(url, postData, function(data){
                    var al = $("#my-alert");
                    al.find("div[class='am-modal-bd']").html(data.desc);
                    al.modal('open');
                    var result = data.res;
                    $('.am-modal-btn').on('click',function(){
                      if(result == true){
                            window.location.reload();
                        }
                    });
                },"json").error(function(e){
                alert(e.responseText); });
            }
        });
        break;
    }
  });
});
