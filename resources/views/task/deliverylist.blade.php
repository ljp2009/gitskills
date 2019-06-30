@extends('layouts.list')
@section('title',$title)
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
    <div id="listDataContainer" style="width:100%"></div>
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
$.ymListItem.bindListDelete(function(id, callback){
    $.post('/{{$type}}/delete',{
       '_token' :$.ymFunc.getToken(),
        'id':id
    }, function(data){
        callback(data.res, id);
    });
});
@stop

