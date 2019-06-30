
@foreach($models as $model)
<div class="ym_cm_card" id="ym_detail_list_item_{{$model->id}}">
    <div class="ym_cm_listitem">
        <div class="ym_cm_listitem_userbox">
            <img src="{{$model->user->avatar->getPath(2,'64w_64h_1e_1c')}}" onclick="$.ymFunc.goTo('{{$model->user->homeUrl}}')" />
            <label>{{$model->user->display_name}}</label>
            <span>{{$model->createdAt}}</span>
            @if($model->checkOwner())
            <div>
                <a href="javascript:void(0)"  onclick="deleteInfo({{$model->id}})">删除</a>
                <span>|</span>
                <a href="javascript:void(0)" onclick="$.ymListItem.editListItem({{$model->id}})">编辑</a>
            </div>
            @endif
        </div>
        <div class="ym_cm_listitem_contentbox"  onclick="{{isset($isDetail)?'':'$.ymFunc.goTo(\''.$model->url.'\')'}}">
            @if(!is_null($model->title))
            <div>
            <label>{{$model->title}}</label>
            </div>
            @endif
            @if(count($model->imageList)>0)
            <div>
                @foreach($model->imageList as $img)
                <img class="ym_preview_img" src="{{$img->getPath(1,$model->getDefImgFormat())}}" origin="{{$img->getPath(1)}}"/>
                @endforeach
                <br />
            </div>
            @endif
            <div style="margin-bottom:10px;">
            @if(isset($isDetail))
                <pre>{{$model->text}}</pre>
            @else
            {{$model->getShotText()}}
            <br />
            @endif
            @if($model->label)
            <span class="span_label">{{$model->label}}</span>
            @endif
            </div>
        </div>
        <div class="ym_cm_listitem_controlbox">
            <label onclick="$.ymListItem.likeListItem('{{$model->resource}}',{{$model->id}},this)">
                <i class="{{$model->checkUserLike()?'ymicon-heart':'ymicon-heart-o'}}"></i><span>{{$model->getLikeCount()}}</span>
            </label>
            <label onclick="_YMShowShare.show('{{$model->url}}')">
                <i class="ymicon-share"></i><span>分享</span>
            </label>
            <label onclick="$.ymListItem.reportListItem('{{$model->resource}}',{{$model->id}},this)">
                <i class="ymicon-alter"></i><span>举报</span>
            </label>
            <label onclick="$.ymFunc.goTo('{{$model->url}}')">
                <i class="ymicon-comment"></i><span>{{$model->getDiscCount()}}</span>
            </label>
        </div>
    </div>
</div>
@endforeach
