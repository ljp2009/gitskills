@extends('layouts.block')
@section('title')
    {{$title}}
@stop
@section('content')
  @section('serverload')
    <link href="/css/list.css" rel="stylesheet" />
    <link href="/css/ym_dialog.css" rel="stylesheet" />
    <script src="/assets/common/commonDialog.js"></script>
    <input type="hidden" id="commonItemId" value="{{$id}}">
    <input type="hidden" id="commonItemResource" value="{{$resource}}">
    <input type="hidden" id="dimenfo_id">
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>$title])
    <?php $model = $value[0]; ?>
    <div class="ym_cm_card" id="ym_detail_list_item_{{$model->id}}">
        <div class="ym_cm_listitem">
            <div class="ym_cm_listitem_userbox">
                <img src="{{$model->user->avatar->getPath(2,'64w_64h_1e_1c')}}" onclick="$.ymFunc.goTo('{{$model->user->homeUrl}}')" />
                <label>{{$model->user->display_name}}</label>
                <span>{{$model->createdAt}}</span>
                @if($model->checkOwner())
                <div>
                    <a href="javascript:void(0)" id="deleteBtn" onclick="deleteDimenfo({{$model->id}})">删除</a>
                    <span>|</span>
                    <a href="javascript:void(0)" onclick="$.ymListItem.editListItem({{$model->id}})">编辑</a>
                </div>
                @endif
            </div>
            <div class="ym_cm_listitem_contentbox">
                @if(!is_null($model->title))
                <div>
                <label>{{$model->title}}</label>
                </div>
                @endif
                <div style="margin-bottom:10px;">
                    <pre>{!!$model->getFormatText()!!}</pre>
                    @if($model->label)
                    <span class="span_label">{{$model->label}}</span>
                    @endif
                </div>
                @if(count($model->imageList)>0)
                <div style="width:100%">
                    @foreach($model->imageList as $img)
                    <img style="max-width:100%;width:auto;" src="{{$img->getPath(1)}}"/>
                    @endforeach
                </div>
                @endif
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
                <label>
                    <i class="ymicon-comment"></i><span>{{$model->getDiscCount()}}</span>
                </label>
            </div>
        </div>
    </div>
    <div class="ym_cm_card">
        <div class="ym_lzdiv ym_comment_container" viewpath="/discussion/all-{{$resource}}-{{$id}}"></div>
    </div>
    @include('partview.commentbar', ['resource'=>$resource, 'resourceId'=>$id])
    @include('partview.share')
  @stop
  @parent
@stop
@section('runScript')
<script src="/js/listload.js"></script>
<script type="text/javascript">
    $.ymListItem.bindListEdit(function(id){
       $.ymFunc.goTo('/{{$type}}/edit/'+id);
    });

    //删除帖子
    function deleteDimenfo(id){
        $('#dimenfo_id').val(id);
        $('#dimenfo_id').click();
    }
    //绑定删除按钮
    $('#dimenfo_id').commonDialog({
        type:'confirmAndCancelDialog',
        content:'您，确认要删除？'
    })
    .bind('confirmAndCancelDialog', function(res){
        $id = $("#dimenfo_id").val();
        $.post('/{{$type}}/delete', {
            '_token':$.ymFunc.getToken(),
            'id':$id
          }, function(data){
                $.ymFunc.back();
          }).error(function(e){
            alert(e.responseText);
          });
    }, null);
</script>
@stop
