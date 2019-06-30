<div class="ym_cm_card ym_milestone_box">
@for($i=0;$i<count($items);$i++)
<?php
    $class = 'wait';
    $flag = '';
    if ($items[$i]->status == 'finish') {
        $class = 'finish';
    } else {
        if (!isset($position)) {
            $position = $i;
        }
        if ($items[$i]->isDelay) {
            $class = 'delay';
            $flag = '已延迟';
        } elseif ($items[$i]->isActive) {
            $class = 'active';
            $flag = '进行中';
        }
    }

?>
<div class="ym_milestone {{$class}}">
    <div class="icon"></div>
    <div class="header">
        {{$items[$i]->date}}
        <span>{{$items[$i]->statusName}}</span>
    </div>
    <div class="status"></div>
    <div class="body">{{$items[$i]->text}}</div>
    @if($items[$i]->allowSignIn)
    <button type="button" class="ctrl" onclick="signIn({{$items[$i]->id}})">确认</button>
    @endif
    <div class="position" id='ms{{$i}}'></div>
</div>
@endfor
@if(count($items) == 0)
<div class="ym_milestone empty"> 未设置里程碑 </div>
@endif
</div>
<script type="text/javascript">
function signIn(id){
    if(confirm('您确认这个里程碑的工作已经完成了吗？')){
        var url = '/milestone/signin';
        $.post(url, {
            'taskId':{{$taskId}},
            'id':id,
            '_token':$.ymFunc.getToken()
        }, function(data){
            if(data.res){
                location.reload();
            }else{
                alert('确认失败');
            }
        });
    }
}
</script>
