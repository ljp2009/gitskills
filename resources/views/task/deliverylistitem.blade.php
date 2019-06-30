@foreach($models as $model)
<div class="ym_cm_card" id="ym_detail_list_item_{{$model->id}}">
    <div class="ym_cm_listitem">
        <div class="ym_cm_listitem_userbox">
            <img src="{{$model->user->avatar->getPath(2,'64w_64h_1e_1c')}}" onclick="$.ymFunc.goTo('{{$model->user->homeUrl}}')" />
            <label>{{$model->user->display_name}}</label>
            <span>{{$model->created_at}}</span>
            @if(!$model->isLocked)
            <div>
                <a href="javascript:void(0)" onclick="$.ymListItem.deleteListItem({{$model->id}})">删除</a>
                <span>|</span>
                <a href="javascript:void(0)" onclick="$.ymListItem.editListItem({{$model->id}})">编辑</a>
            </div>
            @endif
        </div>
        <div class="ym_cm_listitem_contentbox" >
            <div style="margin-bottom:10px;">
                <pre>{{$model->text}}</pre>
            </div>
            @if(count($model->image)>0)
            <div>
                @foreach($model->image as $img)
                <img class="ym_preview_img" src="{{$img->getPath(1,'128w_128h_1e_1c')}}" origin="{{$img->getPath(1)}}"/>
                @endforeach
                <br />
            </div>
            @endif
            @if(count($model->attachments)>0)
            <div style="margin-bottom:10px;line-height:20px">
            附件:<br/>
            @foreach($model->attachments as $att)
                <a href="{{$att['url']}}">{{$att['show']}}</a>
            @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
<script >$.ymImgShow.bind('img.ym_preview_img');</script>
