@extends('layouts.list')
@section('title',$title)
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
    <link href="/css/ym_dialog.css" rel="stylesheet" />
    <script src="/assets/common/commonDialog.js"></script>
    <div id="listDataContainer" style="width:100%"></div>
    <input id="discuss_id" style="display:none" />
    @include('partview.share')
@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#listDataContainer",
    "type":"discussion",
    "pageIndex":{{ $page }},
    "listName":"{{$type}}-{{$resource}}-{{$id}}",
    "itemFeature":".ym_comment_box",
});

function switchLikeIcon(resourceName, resourceId, isLike){
    var $items = $('i.ym_comment_box_like[discid='+resourceId+']'); 
    var $span = $('span.ym_comment_box_like_count[discid='+resourceId+']'); 
    var ct = parseInt($span.html());
    if(isLike){
        $items.removeClass('ymicon-heart-o');
        $items.addClass('ymicon-heart');
        $span.html(ct+1);
    }
    else{
        $items.removeClass('ymicon-heart');
        $items.addClass('ymicon-heart-o');
        $span.html(ct-1);
    }
}
function deleteDiscuss(id){
    $('#discuss_id').val(id);
    $('#discuss_id').click();
}
//绑定删除按钮
$('#discuss_id').commonDialog({
        type:'confirmAndCancelDialog',
        content:'您确定要删除这条评论吗?'
    })
    .bind('confirmAndCancelDialog', function(res){
        var id = $("#discuss_id").val();
        //调用删除函数
        $.post('/discussion/delete', {
           '_token':$.ymFunc.getToken(),
           'id':id
        }, function(data){
          if(data.res){
            $('.discuss_flag_'+id).remove();
          }
        }).error(function(e){
          alert(e.responseText);
        });
    }, null);
@stop

