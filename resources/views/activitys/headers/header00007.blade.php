<style type"text/css">
.cus_header{ width:100%; text-align:center; overflow:hidden; }
.cus_header>img{ width:100%; min-width:400px; }
body{ background-image:url('/imgs/act07/back-0.png'); }
.cus_line{ 
    margin:25px 5% 15px 5%;
    border:dashed 4px #fff;
    background-color:#fed2d1;
    border-radius:20px;
    font-size:1.4rem;
    padding:0 15px 15px 15px;
    line-height:25px;
 }
.cus_line>label{
    display:block;
    margin: -19px auto 10px auto;
    font-size:2.4rem;
    font-weight:bold;
    width:200px;
    color:#929292;
    text-align:center;
}
.cus_line_color{
    background-color:#fff;
    border:dashed 4px #fed2d1;
}
.cus_line_color>span{
    font-weight:bold;
    color:#ff3193;
}
.join_button{
    width: 140px;
    height: 30px;
    font-size: 1.3rem;
    line-height:16px;
    outline:none;
    filter:chroma(color=#000000);
    margin: 15px auto auto auto;
    border:none;
    background-color: #ef7c1e;color:#fff;
    -moz-border-radius: 18px 18px 18px 18px;      /* Gecko browsers */
    -webkit-border-radius: 18px 18px 18px 18px;   /* Webkit browsers */
    border-radius:18px 18px 18px 18px;            /* W3C syntax */
    vertical-align:middle ;
    display:block;
}
@media only screen and (min-width:420px)
{
    .cus_line{
        font-size:1.9rem;
        line-height:35px;
    }
    .cus_line>span{
        font-size:1.9rem;
        line-height:35px;
    }
}
</style>
<div class="cus_header">
    <img src="/imgs/act07/baner.png" />
</div>
<div class="cus_line" style="text-align:center">
<label>活&nbsp;动&nbsp;结&nbsp;果</label>
截止到今天我们的活动圆满结束啦~奖品会陆续放出，敬请期待~QAQ
<br />
</div>
<div class="cus_line cus_line_color">
<label>活&nbsp;动&nbsp;奖&nbsp;励</label>
<span>
一等奖1名 300元以内在售黏土<br />
二等奖1名 200元小裙子一条<br />
三等奖1名 100元假发基金
</span>
<br>
<br>
<span style="color:#ff8e14">
优秀奖第4-10名 有妹表情包变色杯<br>
优秀奖11-20名 有妹表情包抱枕
</span>
<br>
<br>
另外，投稿并分享到微博艾特官微 o有妹酱o 的小可爱还会得到有妹准备的“最勤勉小天使”特别红包
</div>
@include('partview.tabswitchbar',[ 'list'=> [
    'rank'=>['name'=>'作品排行榜',   'url'=>'/activity/list/rank/0/'.$model->id],
    'join'=>['name'=>'最新参赛作品', 'url'=>'/activity/list/join/0/'.$model->id]
    ], 'active'=>$listName, 'inPage'=>true])
