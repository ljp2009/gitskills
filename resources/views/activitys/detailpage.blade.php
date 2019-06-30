@extends('layouts.list')
@section('title','活动')
@section('listcontent')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'活动详情'])
<style type"text/css">
.ym_act_content{
    width:100%;
    max-width:1366px;
    margin-left:auto;
    margin-right:auto;
}
.ym_act_partner{
    position:relative;
    display:block;
    float:left;
    background:#fff;
    overflow:hidden;
    border:solid 1px #e2e2e2;
    margin-top:10px;
}
@media only screen and (max-width:420px) {
.ym_act_partner{ width:100%;}
}
@media only screen and (min-width:421px) and (max-width:1024px) {
.ym_act_partner{ width:33.33333%;height:540px}
}
@media only screen and (min-width:1025px) {
.ym_act_partner{ width:25%; height:465px;}
}
.ym_act_img_show{ 
    padding:15px;
    background-color:#fff;
    overflow:hidden;
    width:100%;
    text-align:center;
    position:relative;
}
.ym_act_img_show>span{
    position:absolute;
    height:15px;
    width:100%;
    background-color:#fff;
    left:0;
    right:0;
    bottom:0;
}
.ym_act_img_user{
    position:absolute;
    bottom:0;
    left:0;
    background-color:#fff;
    border-top:solid 1px #e2e2e2;
    border-bottom:solid 1px #e2e2e2;
    width:100%;
    text-align:center;
    height:70px;
}
.ym_act_img_user>img{
    position:absolute;
    left:10px;
    top:7px;
    border-radius:25px;
    height:50px;
    width:50px;
    border:solid 1px #e2e2e2;
}
.ym_act_img_user>label{
    position:absolute;
    left:70px;
    top:15px;
    font-size:1.4rem;
}
.ym_act_img_user>span{
    position:absolute;
    left:70px;
    top:35px;
    font-size:1.2rem;
    color:#929292;
}
.ym_act_img_user>button{
    border-radius:7px;
    position:absolute;
    border:none;
    height:35px;
    width:80px;
    right:15px;
    top:15px;
    font-size:1.3rem;
    border:solid 1px #f7535a;
    background-color:#fff;
    color:#f7535a;
}
.ym_act_img_user>button.active{
    background-color:#f7535a;
    border:solid 1px #f7535a;
    color:#fff;
}
</style>
@if(view()->exists('activitys.headers.header'.sprintf("%05d", $model->id)))
    @include('activitys.headers.header'.sprintf("%05d", $model->id),['model'=>$model])
@else
    @include('activitys.headers.header00000',['model'=>$model])
@endif
<div id="act_ul_content" class="ym_act_content"></div>
<div style="clear:both">&nbsp;</div>
<script>
function afterVote(res, id, isLike){
    var $btn = $('#vote_'+id);
    var $span = $btn.find('span');
    if(isLike){ 
        $btn.addClass('active');
        $span.html(parseInt($span.html())+1);
    }
    else{ 
        $span.html(parseInt($span.html())-1);
        $btn.removeClass('active');
    }
}
</script>
@stop
@section('bindlist')
//<script>
$('#act_ul_content').html('');
list.bind({
    "container"   : "#act_ul_content",
    "type"        : "activity",
    "loadSize"    : "4",
    "maxSize"     : "60",
    "parentId"    : {{ $pid }},
    "pageIndex"   : {{$page}},
    "listName"    : "{{$listName}}",
    "itemFeature" : ".ym_cm_listitem",
})
@stop
