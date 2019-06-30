@extends('layouts.list')
@section('title',$title)
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
    <link href="/css/ym_dialog.css?a=1" rel="stylesheet" />
    <script src="/assets/common/commonDialog.js?a=1"></script>
    <div id="listDataContainer" style="width:100%"></div>
    <input id="detail_id" style="display:none" />
    @if(isset($hideAdd) && $hideAdd)
        @include('partview.listaddbar',['url'=>'/'.$type.'/create/'.$id])
    @endif
    @include('partview.share')
@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#listDataContainer",
    "type":"{{$type}}",
    "parentId":{{ $id }},
    "pageIndex":{{ $page }},
    "listName":"{{$listName}}",
    "itemFeature":".ym_cm_listitem",
});
$.ymListItem.bindListEdit(function(id){
   $.ymFunc.goTo('/{{$type}}/edit/'+id);
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
        $.post('/{{$type}}/delete', {
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

