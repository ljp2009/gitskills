@foreach($taskList as $task)
<div class="ym_cm_card">
    <div class="ym_tasklist_item" onclick="$.ymFunc.goTo('{{$task->detailUrl}}')">
    <div class="ym_tasklist_item_title">
        {{$task->title}}
    </div>
    <div class="ym_cm_listitem_userbox">
        <img src="{{$task->user->avatar->getPath(2,'64w_64h_1e_1c')}}" />
        <label>{{$task->user->display_name}}</label>
        <span>发布时间：{{$task->publish_date}}</span>
        <i class="ymicon-right"></i>
    </div>
    <div class="ym_tasklist_item_tagbar">
        <div class="ym_tasklist_item_tag">{{$task->getModelName()}}&nbsp;|&nbsp;{{$task->getSkillName()}}&nbsp;|&nbsp;{{$task->getStepName()}}</div>
        <div class="ym_tasklist_item_time">交付时间：{{$task->delivery_date}}</div>
        <div class="ym_tasklist_item_money">
            <label>{{$task->amount}}</label>
            <span>完成奖励(金币)</span>
        </div>
    </div>
    </div>
</div>
@endforeach
