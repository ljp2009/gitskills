@foreach($models as $model)
<div class="ym_cm_listitem ym_act_partner">
    <div class="ym_act_img_show" onclick="$.ymFunc.goTo('{{$model->detailUrl}}')">
        <img src="{{$model->cover->getPath()}}">
        <span>&nbsp;</span>
    </div>
    <div class="ym_act_img_user">
        <img src="{{$model->user->avatar->getpath(1,'80w_80h_1e_1c')}}">
        <label>{{$model->user->display_name}}</label>
        <span>发布于{{$model->created_at}}</span>
        @if(isset($isFinish) && $isFinish)
        <button id="vote_{{$model->resource_id}}" type="button">
            得票<i class="ymicon-heart"></i>
            <span>{{$model->score}}</span>
        </button>
            @else
        <button id="vote_{{$model->resource_id}}" type="button"
            class="{{$model->voted?'active':''}}"
            onclick="$.ymFunc.switchLike('{{$model->resource}}',{{$model->resource_id}}, afterVote)" >
            得票<i class="ymicon-heart"></i>
            <span>{{$model->voteCount}}</span>
        </button>
            @endif
    </div>
</div>
@endforeach
