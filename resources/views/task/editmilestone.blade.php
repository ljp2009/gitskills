@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'pageTitle'=>'设置里程碑', 'right'=>'home' ])
<link rel='stylesheet' href='/css/ym_publish.css'>
<input type="hidden" id="taskId" value="{{$taskId}}"/>
    <div class="ym_taskmg_page" id="mainPage">
        <div class=''>
            <input type="hidden" id="ym_value_selector_key" value ="" />
            <input type="hidden" id="ym_value_selector_value" value ="" />
            <ul class="ym_taskmg_milestone am-avg-sm-1"  id="milestoneContainer"></ul>
        </div>
        <div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
              <ul class="am-navbar-nav am-cf am-avg-sm-1">
                  <li> <a href="javascript:void(0)" onclick="showEditPartview('milestone',0)">
                        <span class="am-icon-plus"></span><span class="am-navbar-label">添加里程碑</span>
                    </a> </li>
              </ul>
        </div>
    </div>
    <div class="ym_taskmg_page" id="editPage" style='z-index:1000'></div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除里程碑</div>
    <div class="am-modal-bd">
      你，确定要删除这个里程碑吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript">
function saveMileStone(id, date, text){
    $.post('/milestone/manage/save',{
        'taskid':getId(),
        '_token':getToken(),
        'id': id,
        'date':date,
        'text':text },
     function(data){
        if(data.res){
            loadMilestone();
            back();
        }
    }).error(function(e){
        alert(e.responseText);
    });
}
function editMilestone(id){
    showEditPartview('milestone',id, '/milestone/manage/'+getId()+'/'+id);
}
function removeMilestone(id){
  $('#my-confirm').modal({
      relatedTarget:id,
          onConfirm: function(e) {
              $.post('/milestone/manage/delete',{
                  '_token':$('meta[name="csrf-token"]').attr('content'),
                   'milestoneId':this.relatedTarget
              },
              function(data){
                if(data.res){
                    loadMilestone();
                }
              }).error(function(e){
                  alert(e.responseText);
              });
          },
          onCancel: function(e) {
          }
      });
}
function loadMilestone(){
    $.get('/milestone/manage/all/'+getId(),function(res){
            var data = res.data;
            var container = $('#milestoneContainer');
            container.html('');
            for(var i=0; i<data.length; i++){
                var cls = i>0?'ym_taskmg_item':'ym_taskmg_item_top';
                var $item = $('<li class="ym_taskmg_milestone_item">'+
                           '<div class="ym_taskmg_milestone_date">'+ data[i]['date']+
                           '<i class="am-icon-edit ym_taskmg_milestone_btn" onclick="editMilestone('+data[i]['id']+')"></i>'+
                           '<i class="am-icon-trash ym_taskmg_milestone_btn" onclick="removeMilestone('+data[i]['id']+')"></i>'+'</div>'+
                           '<div class="ym_taskmg_milestone_text">'+data[i]['text']+'</div>'+
                           '</li>');
                if(data[i]['isDelay']){
                    $item.addClass('error');
                }
                container.append($item);
            }
        }).error(function(e){
        alert(e.responseText);
    });
}
showEditMainPage(true);
loadMilestone();
</script>
@stop
