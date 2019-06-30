@extends('layouts.block')
@section('title','活动')
@section('content')
@section('serverLoad')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'IDO COSER 评选'])
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
    .custom_top10{
        padding:3px;
        margin:0;
        list-style:none;
        width:100%;
    }
    .custom_top10>li{
        display:block;
        width:25%;
        padding:3px;
        float:left;
        position:relative;
    }
    .custom_top10>li>img{
        border-radius:5px;
    }
    .custom_top10>li>span{
        position:absolute;
        right:15px;
        bottom:5px;
        color:#ef7c1e;
        height:20px;
        line-height:20px;
        width:20px;
        text-align:center;
        vertical-align:middle;
        display:block;
        border-radius:10px;
        background-color:rgba(255,255,255,0.5);
        font-size:1.4rem;
        font-weight:bold;
    }
    .custom_top10>li:first-child{
        width:50%;
    }
    .custom_top10>li:nth-child(2){
        width:50%;
    }
    .custom_container_clear{
        clear:both;
        margin-bottom:50px;
    }
    .ym_pop_shade{
        text-align:center;
        vertical-align:middle;
    }
    .ym_pop_shade>img{
        display:inline-block;
        max-width:95%;
        max-height:90%;
    }
</style>
<div class="listDataContainer" width="100%">
    <div class='act_show'>
        <div class='the_title'>IDO漫展Coser评选</div>
        <div class="listtag">参与人数：不限</div>
        <div class="listdate">活动进行中</div>
        <a href="/custom/ido21/vote" class="tabBlock-tab join_button" >我去投票</a>
    </div>
    <div class="common">
        <div id="act_des_content" class="act_border">
            <pre style="margin:0 auto;color:#929292;font-size:1.3rem;border:none;background-color: #fff;">
清明节的漫展已经结束，我们在漫展上拍摄了好多coser，哪个coser是你喜欢的类型呢？请参与我们的漫展投票活动吧。
            </pre>
            <div style="border-bottom:solid 3px #ef7c1e" >Coser Top 10</div>
            <ul class="custom_top10">
            @foreach($votes as $vote)
                <li>
                    <img originsrc="{{$vote['detailUrl']}}" src="{{$vote['header']}}"  />
                    <span>{{$vote['index']}}</span>
                </li>
            @endforeach
            </ul>
            <div class="custom_container_clear"></div>
        </div>
    </div>
</div>
	@show
	@parent
	@section('runScript')
    <script>
$(document).ready(function(){
        $('.custom_top10').find('img').on('click', function(){
            var detailUrl = $(this).attr('originsrc');
            var shade = $('<div class="ym_pop_shade"><img src="'+detailUrl+'"></div>');
            $('body').append(shade);
            shade.css('line-height', shade.height()+'px');
            shade.on('click', function(){$('.ym_pop_shade').remove();});
        });
});
</script>
	@stop
@stop
