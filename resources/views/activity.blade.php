@extends('layouts.list')
@section('title','活动')
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$model->title])
<style type="text/css">
    html{font-size: 62.5%;/*10 ÷ 16 × 100% = 62.5%*/}
    body{background-color: #eee;margin: 0 auto;}
    .common{background-color: #fff;border-top: 1px solid #e2e2e2;}
    ul{padding-left: 0em;margin:0 auto;}
    li{list-style-type:none;}
    .act_bg{background-color: #fff;width: 100%;}
    .act_border{padding-top: 15px;margin-right: 10px;margin-left: 10px;padding-bottom: 20px;border-bottom: 1px solid #e2e2e2;}
    .act_pic{}
    #act_pic img{clear:left;width:100%;}
    .act_title{float: left;}
    @media screen and (min-width: 769px) {
        img {
            width: 100%;
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
    .act_list_head{margin-right: 15px;margin-left: 15px;border-bottom: 1px solid #e2e2e2;text-align: center;padding-top: 15px;padding-bottom: 15px;}
    .tabBlock-tab {
        margin-left: -9px;
        background-color: white;
        border-left-style: solid;
        border-top: solid;
        border-bottom: solid;
        border-right: solid;
        border-width: 1px;
        border-color: #ef7c1e;
        color: #ef7c1e;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        position: relative;
        font-size: 1.2rem;
        -webkit-transition: 0.1s ease-in-out;
        transition: 0.1s ease-in-out;

    }
    .tabBlock-tab-left{
        height: 25px;
        line-height: 12px;
        font-size: 1.3rem;
        -moz-border-radius: 3px 0px 0px 3px;      /* Gecko browsers */
        -webkit-border-radius: 3px 0px 0px 3px;   /* Webkit browsers */
        border-radius:3px 0px 0px 3px;            /* W3C syntax */}
    .tabBlock-tab-right{
        height: 25px;
        line-height: 12px;
        font-size: 1.3rem;
        -moz-border-radius: 0px 3px 3px 0px;      /* Gecko browsers */
        -webkit-border-radius: 0px 3px 3px 0px;   /* Webkit browsers */
        border-radius:0px 3px 3px 0px;            /* W3C syntax */}
    .tabBlock-tab-center{
        height: 25px;
        line-height: 12px;
        font-size: 1.3rem;
        -moz-border-radius: 0px 0px 0px 0px;      /* Gecko browsers */
        -webkit-border-radius: 0px 0px 0px 0px;   /* Webkit browsers */
        border-radius:0px 0px 0px 0px;            /* W3C syntax */}
    .is-active{background-color: #ef7c1e; color: #fff;}
    .listtitle{margin-top: 10px}
    .listtag{margin-top: 15px;font-size: 1.2rem;color: #a5a5a5;}
    .listdate{margin-top: 10px;font-size: 1.2rem;color: #a5a5a5;}
    .act_show{text-align: center;border-bottom: 1px solid #e2e2e2;margin-bottom: 15px;background-color: #fff;padding-bottom: 30px;padding-top: 32px;}
    .the_title{color: #383838;font-size: 1.4rem;}
    .join_button{
        width: 140px;
        height: 30px;
        font-size: 1.3rem;
        line-height:16px;
        outline:none;
        filter:chroma(color=#000000);
        margin-top: 15px;
        background-color: #ef7c1e;color:#fff;
        -moz-border-radius: 18px 18px 18px 18px;      /* Gecko browsers */
        -webkit-border-radius: 18px 18px 18px 18px;   /* Webkit browsers */
        border-radius:18px 18px 18px 18px;            /* W3C syntax */
        vertical-align:middle ;
    }
    .act_per_img{width:40px; height:40px; border-radius:40px; }
    .act_list_name{width: 150px;line-height: 2px;font-size: 1.3rem;margin-top: 10px;}
    .public_time{width: 100px;line-height: 5px;color: #aaa;font-size: 1.2rem;}
    .act_per_icon{width:23px;height:20px;margin-top: 45%;margin-right: 5px; font-size: 3.3rem;color:#f7535a;}
    .likes{float: right;}
    .likes_num{float:right;margin-top:20px;}
    .pinglun{float:right;margin-top:20px;margin-right:10px;}
</style>
<div class="listDataContainer" width="100%">
    <div class='act_show'>
        <div class='the_title'>{{$model->title}}</div>
        <div class="listtag">参与人数：{{$model->count_num}}</div>
        @if($model->days <= 0)
        <div class="listdate">活动已结束</div>
        <a href="#" class="tabBlock-tab join_button" style="border-color: #ccc;background-color: #aaaaaa;color:#fff;">立即参与</a>
        @elseif($model->from_date > date('Y-m-d H:i:s'))
        <div class="listdate">活动未开始</div>
        <a href="#" class="tabBlock-tab join_button" style="border-color: #ccc;background-color: #aaaaaa;color:#fff;">立即参与</a>
        @else
        <div class="listdate">剩余时间：{{$model->days}}天</div>
        <a href="{{$joinLink}}" class="tabBlock-tab join_button">立即参与</a>
        @endif
        <hr />
        <pre style="margin:0 auto;color:#929292;font-size:1.3rem;border:none;background-color: #fff;text-align:justify;padding:15px">{{$model->text}}</pre>
    </div>

    <div class="common">
        <div class="act_list_head">
            <ul class="tabBlock-tabs">
                <li id="zuo_pin_button" class="tabBlock-tab tabBlock-tab-left <?php if ($listName == 'get_join_list_data') {
    echo 'is-active';
} ?>" onclick="set_hid(0)">作品展示</li>
                <li id="pai_hang_button" class="tabBlock-tab tabBlock-tab-center <?php if ($listName == 'get_ranking_list_data') {
    echo 'is-active';
} ?>" onclick="set_hid(1)">作品排名</li>
                <li id="act_des_button" class="tabBlock-tab tabBlock-tab-right" onclick="set_hid(2)">活动结果</li>
            </ul>
        </div>
        <ul id="act_ul_content">

        </ul>
        <div id="act_des_content" class="act_border" style="display:none;">
            <div style="border-bottom: 1px solid #e2e2e2;padding-bottom: 10px;margin-bottom: 10px;">
            @if(is_null($model->result))
            <pre style="margin:0 auto;color:#929292;font-size:1.3rem;border:none;background-color: #fff;">活动进行中……</pre>
            @else
            <pre style="margin:0 auto;color:#929292;font-size:1.3rem;border:none;background-color: #fff;">{{$model->result}}</pre>
            @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('bindlist')
//<script>
$('#act_ul_content').html('');
list.bind({
    "container" : "#act_ul_content",
    "type"      : "{{$type}}",
    "parentId"  : {{ $model->id }},
    "pageIndex" : {{$page}},
    "listName"  : "{{$listName}}",
    "itemFeature":".ym_cm_listitem",
})
@stop
