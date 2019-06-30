@extends('layouts.list')
@section('title','参与用户列表')
@section('listcontent')
    <link href="/css/ipdetail.css" rel="stylesheet" />
    <link href="/css/ym_userlist.css" rel="stylesheet" />
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'参与用户列表'])

    <div id="userlistcontainer" style="padding-top:1.5rem"></div>

@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#userlistcontainer",
    "type":"ip",
    "pageIndex":{{ $page }},
    "listName":"user",
    @if(!empty($pid))
        "parentId":{{ $pid }},
    @endif
});
@stop


