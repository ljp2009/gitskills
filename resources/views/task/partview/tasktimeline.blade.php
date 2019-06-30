<div class="ym_cm_card ym_milestone_box">
@if(count($list)>0)
<div class="ym_milestone active">
    <div class="icon"></div>
    <div class="header">
        {{$list[$i]['date']}}
    </div>
    <div class="status"></div>
    <div class="body">{{ $list[$i]['text'] }}</div>
</div>
@else
@endif
<div class="ym_milestone empty"> 项目还没啥进展 </div>
</div>
