@extends('layouts.block')
@section('content')
	@section('serverLoad')
<style type="text/css">
.custom_title{
    height:160px;
    width:100%;
    font-size:1.4rem;
    line-height:24px;
    color:#383838;
    background-color:#fff;
    border-bottom:solid 1px #e2e2e2;
    padding:10px;
    position:relative;
}
.custom_title>.logo{
    position:absolute;
    width:50px;
    left:15px;
    bottom:15px;
}
.custom_title>.banner{
    position:absolute;
    height:100%;
    top:0;
    right:0;
}
.custom_title>.label{
    position:absolute;
    height:100%;
    top:20px;
    left:20px;
    color:#ff6666;
    font-size:2.4rem;
    line-height:3rem; 
}
.custom_info{
    height:88px;
    margin:5px;
    border:solid 2px #be4b48;
    border-radius:8px;
    background: linear-gradient(to bottom, #ffe3e3, #ffa5a5);
    position:relative;
    padding:5px 5px 5px 80px;
    font-size:1.3rem;
    color:#383838;
    line-height:18px;
    box-shadow:3px 3px 3px #888888;
    margin-bottom:20px;
}
.custom_info>.umeiii{
    position:absolute;
    left :2px;
    bottom:2px;
    height:75px;

}
.custom_div{
    width:100%;
    max-width:700px;
    margin:0 auto;
    padding:15px;
    /*background-color:#c3c2cc;*/
    background-color:#ffe3e3;
}
.custom_container{
    padding:3px;
    margin:0;
    list-style:none;
    width:100%;
   /* background-color:#c3c2cc;*/
}
.custom_container_clear{
    clear:both;
}
.custom_vote_item{
    display:block;
    width:44%;
    margin:3%;
    padding:15px;
    float:left;
    position:relative;
    /*background-color:#a0a0b4;*/
    background-color:#ffa5a5;
}
.custom_vote_item>img{
    width:100%;
}
.custom_vote_label{
    height:25px;
    line-height:25px;
    vertical-align:middle;
    left:3px;
    right:3px;
    bottom:15px;
    text-align:center;
    font-size:1.3rem;
    color:#fff;
    position:absolute;
    margin:0;
/* background-color:#57548d; */
 background-color:#ff4242; 
}
.custom_vote_label>.checkbox{
    position:absolute;
    height:15px;
    line-height:15px;
    width:15px;
    top:5px;
    left:5px;
    background-color:#fff;
    color:#ff4242;
    font-size:13px;
    font-weight:bolder;
}
.custom_vote_label>.checkbox.selected:before{
    content:"√"
}
.custom_top_title{
    height:30px;
}
.custom_top_container{
    background-color:#fff;
}
.custom_top_container li:first-child{
    background-color:#ffcd00;
}
.custom_top_container li:nth-child(2){
    background-color:#feec9f;
}
.custom_top_container li:nth-child(3){
    background-color:#eee0c8;
}
.custom_top_item{
    display:block;
    width:18%;
    margin:1%;
    margin-bottom:30px;
    padding:5px 5px 0 5px;
    background-color:#e2e2e2;
    float:left;
    position:relative;
    border-radius:3px;
}
.custom_top_item>img{
    width:100%;
}
.custom_top_item>label{
    font-size:1.2rem;
    display:block;
    text-align:center;
    margin:0;
}
.custom_top_item>label.vote_name{
    background-color:#fff;
    margin:5px -5px 0 -5px;

}
.custom_footer{
    width:100%;
    height:35px;
    text-align:center;
    vertical-align:middle;
    bottom:0;
    position:relative;
}
.custom_footer>button{
    color:#fff;
    font-size:1.4rem;
    height:35px; 
    outline:none;
    margin:5px auto;
    display:block; 
    width:120px;
    border:solid 2px #be4b48;
    border-radius:8px;
    background: linear-gradient(to bottom, #ff9898, #ff4242);
    position:relative;
    box-shadow:2px 2px 2px #888888;
}
.custom_footer>.ymicon-switch{
    position:absolute;
    display:block;
    right:10px;
    top:5px;
    font-size:1.4rem;
    height:35px;
    color:#f7535a;
    line-height:35px; 
    padding:0;
    margin:0;
    text-align:center;
    vertical-align:middle;
}
.custom_footer>.ymicon-switch>span{
    height:35px;
    line-height:35px; 
    font-size:1.4rem;
    display:block;
    float:right;
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
.ym_pop_shade>.alert{
    display:inline-block;
    height:80px;
    width:150px;
    line-height:25px;
    text-align:center;
    vertical-align:middle;
    color:#383838;
    font-size:1.4rem;
    background-color:#fff;
    border-radius:7px;
    padding-top:14px;

}
</style>
<input type="hidden" id="vid" value="">
<div class="custom_title">
    <label class="label">IDO 21 届漫展<br/>Coser 人气票选</label>
    <img class="banner" src="http://img.umeiii.com/dimpub/def-1491741754769-.jpg@.png">
    <img class="logo" onclick="$.ymFunc.goTo('/reshall');"
         src="http://img.umeiii.com/dimpub/def-1491741760816-.jpg@.jpg">
</div>
<div class="custom_info" id="info_before">
    <img class="umeiii" src="http://img.umeiii.com/dimpub/def-1491746862137-.jpg@.png">
    票选规则<br />
    时间：4月2日-4月20日<br />
    资格：需登陆注册获取投票资格，投票次数及票数不限哦
</div>
<div class="custom_info" id="info_after" style="display:none">
    <img class="umeiii" src="http://img.umeiii.com/dimpub/def-1491746862137-.jpg@.png">
    感谢您投票参与(=・ω・=)<br />
    <br />
    如有更多Coser美图，欢迎来撩我哦～<br />
    QQ群号：617454468
</div>
<div class="custom_div">
    <ul class= "custom_container"></ul>
    <div class="custom_container_clear"></div>
    <div class="custom_footer">
        <button type ="button" >提交选票</button>
        <i class ="ymicon-switch" ></i>
    </div>
</div>
<div class="ym_cm_card">
    <div class="ym_lzdiv ym_comment_container" viewpath="/discussion/newest-ido-21"></div>
</div>
	@show
	@parent
@include('partview.commentbar', ['resource'=>'ido', 'resourceId'=>21])
	@section('runScript')
<script type="text/javascript">
$(document).ready(function(){
    loadVotes();
    $('.custom_footer').find('button').on('click', function(){
        sendVotes(true);
    });
    $('.custom_footer').find('.ymicon-switch').on('click', function(){
        $('.ymicon-switch').html('<span>刷新中</span>');
        sendVotes(false);
        setTimeout(function(){
            loadVotes();
        }, 1000);
    });
});
function sendVotes(flag){
    var values = [];
    $('.custom_vote_item.selected').each(function(){
        var img = $(this).find('img');
        var value = img.attr('code');
        values.push(value);
    });
    $.post('/custom/ido21/vote',{
        '_token' : $.ymFunc.getToken(),
        'vid'    : $('#vid').val(),
        'values' : values
    }, function(data){
        if(!data.res){
            $.ymFunc.goTo(data.url);
            return;
        }
        if(flag){
            //showAlter();
            showResult(data.info);
        }
    });
}
function loadVotes(){
    $.get('/custom/ido21/load-vote', function(data){

        showResult(data.info);
        return;
        if(typeof(data.debug) != 'undefined'){
            for(var i=0;i<data.debug.length; i++) {
                console.log(data.debug[i]);
            }
        }
        $('#vid').val(data.info);
        var container = $('.custom_container');
        container.html('');
        for(var i=0; i<data.votes.length; i++){
            var vote = data.votes[i];
            container.append('<li class="custom_vote_item">'+
                '<img originsrc="'+vote.detailUrl+'" src="'+vote.url+'" code="'+vote.code+'" />'+
                '<label class="custom_vote_label">'+
                    '<span class="checkbox"></span>'+
                    vote.name+
                '</label>'+
            '</li>');
        }
        container.find('.custom_vote_label').on('click', function(){
            var li = $(this).parent();
            if(li.hasClass('selected')){
                li.removeClass('selected');
                li.find('.checkbox').html('');
            }else{
                li.addClass('selected');
                li.find('.checkbox').html('√');
            }
        });
        container.find('img').on('click', function(){
            var detailUrl = $(this).attr('originsrc');
            var shade = $('<div class="ym_pop_shade"><img src="'+detailUrl+'"></div>');
            $('body').append(shade);
            shade.css('line-height', shade.height()+'px');
            shade.on('click', function(){$('.ym_pop_shade').remove();});
        });
         $('.ymicon-switch').text('刷新');
    });
}
function showAlter(){
    var shade = $('<div class="ym_pop_shade"><span class="alert">感谢您的参与<br/>您即将进入有妹社区</span></div>');
    $('body').append(shade);
    shade.css('line-height', shade.height()+'px');
    shade.on('click', function(){
        $.ymFunc.goTo('/reshall');
    });
    setTimeout(function(){
        $.ymFunc.goTo('/reshall');
    }, 3000);
}

function showResult(votes){
    $('#info_before').hide();
    $('#info_after').show();
    $('.custom_div').addClass('custom_top_container');
    var container = $('.custom_container');
    container.html('');
        for(var i=0; i<votes.length; i++){
            var vote = votes[i];
            container.append('<li class="custom_top_item">'+
                '<img src="'+vote.header+'" />'+
                '<label class="vote_name">编号'+vote.code+'</label>'+
                '<label>票数'+vote.liked+'</label>'+
            '</li>');
        }
    $('.custom_footer').remove();
}


</script>
	@stop
@stop
