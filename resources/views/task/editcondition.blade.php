@extends('layouts.block')
@section('title','编辑任务')
@section('content')
@section('serverLoad')
<link rel='stylesheet' href='/css/ym_publish.css'>
<input type="hidden" id="taskId" value="{{$task->id}}"/>
<div class="ym_taskmg_body">
    <div class="ym_taskmg_page" id="mainPage">
        <div class="ym_backheader">
            <ul class="am-avg-sm-3">
                <li style="text-align:left" onclick="backToMainEditPage()">
                    <i class="am-icon-angle-left"></i>
                    <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
                </li>
                <li style="text-align:center"><span class="ym_backheader_title">编辑交付条件</span>
                </li>
                <li style="text-align:right">&nbsp;</li>
            </ul>
        </div>
        <div class="ym_taskmg_desc">
            <i class="am-icon-info-circle"></i>&nbsp;&nbsp;任务：{{$task->title}}
        </div>
        <div class='' style="height:auto">
            <input type="hidden" id="ym_value_selector_key" value ="" />
            <input type="hidden" id="ym_value_selector_value" value ="" />
            <ul class="ym_taskmg_milestone am-avg-sm-1"  id="conditionContainer"></ul>
        </div>
        <div style="width:100%;height:50px"></div>
        <div data-am-widget="navbar" class="am-navbar am-cf ym-navbar-default" id="">
              <ul class="am-navbar-nav am-cf am-avg-sm-1">
                  <li> <a href="javascript:void(0)" onclick="showBottomMenu('#menu');">
                        <span class="am-icon-plus"></span><span class="am-navbar-label">添加交付条件</span>
                    </a> </li>
              </ul>
        </div>
    </div>
    <div class="ym_taskmg_page" id="editPage"></div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除条件</div>
    <div class="am-modal-bd">
      你，确定要删除这个交付条件吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<div class="ym_taskmg_page" id="shade" onclick="hideBottomMenu('#menu')"></div>
<div class="ym_taskmg_page" style="background-color:#f5f5f9" id="menu">
    <div class="ym_taskmg_pop_btn" onclick="showAddCondition('1')">添加日期条件</div>
    <div class="ym_taskmg_pop_btn" onclick="showAddCondition('2')">添加数量条件</div>
    <div class="ym_taskmg_pop_btn" onclick="showAddCondition('3')">添加范围条件</div>
    <div class="ym_taskmg_pop_btn" onclick="showAddCondition('4')">添加自定义条件</div>
    <div class="ym_taskmg_pop_btn" onclick="showAddCondition('5')">添加软标准</div>
    <div class="ym_taskmg_pop_btn2" onclick="showAddCondition()">取消</div>
</div>
@show
@parent
@section('runScript')
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript">
function showAddCondition(conditionType){
    if(typeof(conditionType) != 'undefined'){
        showEditPartview('new_condition',conditionType);
    }
    hideBottomMenu('#menu');
}
function validate(type, value, label, text){
    if(value == ''){
        switch(type){
            case 1:
                return '日期不能为空。';
            case 2:
                return '数量不能为空。';
            case 5:
                return '附件不能为空。';
        }
    }
    if(label == ''){
        return '标签不能为空。';
    }
    if(text == ''){
        return '描述不能为空。';
    }
    return '';
}
function saveCondition(id, type, label, value, text){
    var vli = validate(type, value, label, text);
    if(vli != ''){
        showError(vli);
        return;
    }
    $.post('/pubtask/savecondition',{
        'taskId':getId(),
        '_token':getToken(),
        'id': id,
        'type': type,
        'label':label,
        'value':value,
        'text':text },
     function(data){
        if(data.res){
            loadCondition();
            back();
        }
    }).error(function(e){
        alert(e.responseText);
    });
}
function editCondition(type, id){
    showEditPartview('condition',id);
}
function removeCondition(id){
  $('#my-confirm').modal({
      relatedTarget:id,
          onConfirm: function(e) {
              $.post('/pubtask/removecondition',{
                  '_token':$('meta[name="csrf-token"]').attr('content'),
                   'conditionId':this.relatedTarget
              },
              function(data){
                if(data.res){
                    loadCondition();
                }else{
                    alert(data.desc);
                }
              }).error(function(e){
                  alert(e.responseText);
              });
          },
          onCancel: function(e) {
          }
      });
}
function loadCondition(){
    $.get('/pubtask/get-condition-data/'+getId(),function(res){
            var data = res.value;
            var container = $('#conditionContainer');
            container.html('');
            for(var i=0; i<data.length; i++){
                var cls = i>0?'ym_taskmg_item':'ym_taskmg_item_top';
                var item = '<li class="ym_taskmg_milestone_item">'+
                           '<div class="ym_taskmg_milestone_date">'+ data[i]['label']+'：'+data[i]['value']+
                           '<i class="am-icon-edit ym_taskmg_milestone_btn" onclick="editCondition('+data[i]['type']+','+data[i]['id']+')"></i>'+
                           '<i class="am-icon-trash ym_taskmg_milestone_btn" onclick="removeCondition('+data[i]['id']+')"></i>'+'</div>'+
                           '</div>'+
                           '<pre class="ym_taskmg_milestone_text">'+data[i]['text']+'</pre>'+
                           '</li>';
                container.append(item);
            }
        }).error(function(e){
        alert(e.responseText);
    });
}
showEditMainPage(true);
loadCondition();
</script>
@stop
@stop
