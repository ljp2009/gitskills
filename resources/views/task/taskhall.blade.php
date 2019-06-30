@extends('layouts.list')

@section('listcontent')
<link rel="stylesheet" type="text/css" href="/css/ym_task.css?a=1">
@include('partview.headerbar',['left'=>'user', 'center'=>'logo', 'right'=>'none'])
@include('task.partview.filterbar',['order'=>$order, 'filter'=>$filter])
@include('partview.footerbar', ['page'=>'task','addPanel'=>'#ym_task_add_panel'])
<div id="taskContainer" style="padding-bottom:50px;padding-top:50px;">
        <div class="ym_cm_list_none">没有符合条件的任务。</div>
</div>

<div class="am-modal am-modal-no-btn ym_addpanel" tabindex="-1" id="ym_task_add_panel">
  <div class="am-modal-dialog ym_addpanel">
    <div class="am-modal-bd" >
        <ul class="am-avg-sm-1">
            <li class="ym_detail_addbtn">
                <input id="link_add_pk" onclick="$.ymFunc.goTo('/pktaskwizzard')" type="button" value='创建PK任务'/>
            </li>
            <li class="ym_detail_addbtn" style="margin-top:10px">
                <input id="link_add_oppoint" onclick="$.ymFunc.goTo('/appointtaskwizzard')" type="button" value='创建约定任务'/>
            </li>
        </ul>
    </div>
  </div>
</div>

@stop

@section('bindlist')
//<script>
    
	list.bind({
        "container":"#taskContainer",
        "noneItem":"div#taskContainer>.ym_cm_list_none",
		"type":"taskhall",
        "itemFeature":".ym_cm_card",
		"pageIndex":{{ $page }},
		"orderField":$.ymTaskFilterBar.getOrderValue(),
		"searchField":$.ymTaskFilterBar.getFilterValue()
	});

$.ymPopMenu.bind({
'menus':[
    {'text':'竞争任务', 'url':'/pktaskwizzard', 'help':'/building'},
    {'text':'约定任务', 'url':'/appointtaskwizzard', 'help':'/building'},
]
    });

@stop
