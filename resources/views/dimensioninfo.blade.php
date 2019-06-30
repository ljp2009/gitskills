@extends('layouts.list')
@section('title','次元')
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$model->name])
    <link rel="stylesheet" href="/css/ym_dimension.css" />
    <link href="/css/ym_dialog.css" rel="stylesheet" />
    <script type="text/javascript" src="/js/ym_dimension.js"></script>
    <script src="/assets/common/commonDialog.js"></script>
    <div class="ym_dim_header">
        <input type="hidden" id="ym_dim_id" value="{{$model->id}}" />
        <img class="ym_dim_header_cover" src="{{$model->cover->getPath(1,'128w_128h_1e_1c')}}" />
        <div class="ym_dim_header_title">{{$model->name}}</div>
        <div class="ym_dim_header_info">入驻：{{$model->enterSumValue}}人&nbsp;&nbsp;&nbsp;&nbsp;帖子：{{$model->publishSumValue}}&nbsp;&nbsp;&nbsp;&nbsp;领主：{{$model->user->display_name}}</div>
        <div class="ym_dim_header_button">
            @if($model->isEnter == 'N')
            <button class="ym_dim_header_button" type="button" onclick="enterDimension({{$model->id}}, infoAfterEnter)">
                <i class="ymicon-join" style="font-size:15px;"></i><span>入驻次元</span>
            </button>
            @elseif($model->isEnter == 'Y')
            <button class="ym_dim_header_button ym_active" type="button" onclick="enterDimension({{$model->id}}, infoAfterEnter)">
                <i class="ymicon-join" style="font-size:15px;"></i><span>已入驻</span>
            </button>
            @elseif($model->isEnter == 'owner')
            <button class="ym_dim_header_button" type="button" onclick="$.ymFunc.goTo('/dimension/edit/{{$model->id}}')">
                <i class="ymicon-join" style="font-size:15px;"></i><span>编辑次元</span>
            </button>
            @elseif($model->isEnter == 'activity')
            <button class="ym_dim_header_button" type="button">
                <span>活动次元</span>
            </button>
            @else
            <button class="ym_dim_header_button" type="button" onclick="$.ymFunc.goTo('/auth/login')">
                <i class="ymicon-join" style="font-size:15px;"></i><span>请登录</span>
            </button>
            @endif
        </div>
    </div>
    <div id="listDataContainer" style="width:100%"></div>
    <input id="detail_id" style="display:none" />
    @include('partview.listaddbar',['url'=>'/dimpub/publishcreate/'.$model->id])
    @include('partview.share')
@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#listDataContainer",
    "type":"dimpub",
    "listName":"diminfo",
    "parentId":{{ $model->id }},
    "pageIndex":{{ $page }},
    "itemFeature":".ym_cm_listitem",
});
$.ymListItem.bindListEdit(function(id){
   $.ymFunc.goTo('/dimpub/edit/'+id);
});

//删除函数
function deleteInfo(id){
    $('#detail_id').val(id);
    $('#detail_id').click();
}
//绑定删除按钮
$('#detail_id').commonDialog({
        type:'confirmAndCancelDialog',
        content:'您确定要删除这条记录吗?'
    })
    .bind('confirmAndCancelDialog', function(res){
        var id = $("#detail_id").val();
        //调用删除函数
        $.post('/dimpub/delete', {
           '_token':$.ymFunc.getToken(),
           'id':id
        }, function(data){
          if(data.res){
            $.ymListItem.getItem(id).remove();
          }
        }).error(function(e){
          alert(e.responseText);
        });
    }, null);
@stop

