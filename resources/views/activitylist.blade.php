@extends('layouts.list')
@section('title',$title)
<style type="text/css">
    #act_ul{padding-left: 0px;}
    li{list-style-type:none;}
    #act_bg{background-color: #fff;width: 100%;margin-top: 15px;padding-top: 15px;padding-right: 10px;padding-left: 10px;padding-bottom: 40px;
            border-bottom: 1px solid #e2e2e2;border-top: 1px solid #e2e2e2;}
    #act_pic{}
    #act_pic img{clear:left;width:100%;}
    #act_title{float: left;}
    @media screen and (min-width: 769px) {
        img {
            width: 600px;
            top: 18px;
            left: 15px;
        }
    }
    @media screen and (max-width: 768px) {
        img {
            width: 100%;
            top: 18px;
        }
    }
    .listtitle{margin-top: 15px;line-height: 15px;height: 20px;}
    .listtag{float:left;margin-top: 10px;font-size: 1.2rem;color: #aaa;line-height: 12px;}
    .listdate{float: right;margin-top: 10px;font-size: 1.2rem;color: #aaa;line-height: 12px;}
</style>
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
<div id="listDataContainer" style="width:100%">
    <div class="common"><ul id="act_ul"></ul></div>
</div>
@stop
@section('bindlist')
//<script>
list.bind({
    "container":"#act_ul",
    "type":"{{$type}}",
    "pageIndex":{{$page }},
    "listName":"{{$listName}}",
    "itemFeature":".ym_cm_listitem",
    });
@stop

