<!-- 技能 -->
<ul class="ym_avg_4">
@foreach($models as $model)
    <li class="ym-skill-box ym-skill-sel" id="skill-{{$model->id}}">
        <div class="ym-skill-box-imgbac">
            <img class="ym-skill-box-img"
             src="{{$model->image->getPath(0,'96w_54h_1e_1c_100-0-0bgc')}}"
             text="{{$model->intro}}"
             title="{{$model->name}}"
             skillId = "{{$model->id}}"
             origin="{{$model->image->getPath(1)}}">
        </div>
        <label class='ym-skill-box-label'>{{$model->name}}</label>
        @if($model->is_main)
        <label class='ym-skill-box-flag'>奥义</label>
        @endif
    </li>
@endforeach
</ul>
<div style="width:100%; clear:both;">
@if($isOwner)
<script >
$.ymImgShow.bind('img.ym-skill-box-img', [
    {'name':'奥义', 'func':function(img){ return function(){
        var id = $(img).attr('skillId');
        setMainSkill(id);
    };}},
    {'name':'编辑', 'func':function(img){ return function(){
        var id = $(img).attr('skillId');
        $.ymFunc.goTo('/roleskill/edit/'+id);
    };}},
    {'name':'删除', 'func':function(img){ return function(){ 
        var id = $(img).attr('skillId');
        deleteSkill(id);
    };}}
]);
</script>
@else
<script >$.ymImgShow.bind('img.ym-skill-box-img');</script>
@endif

