<style type="text/css">
.listtitle{margin-top: 10px}
.listtag{margin-top: 15px;font-size: 1.2rem;color: #a5a5a5;}
.listdate{margin-top: 10px;font-size: 1.2rem;color: #a5a5a5;}
.act_show{text-align: center;border-bottom: 1px solid #e2e2e2;margin-bottom: 0;background-color: #fff;padding-bottom: 30px;padding-top: 32px;}
.the_title{color: #383838;font-size: 1.4rem;}
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
</style>
<div class='act_show'>
    <div class='the_title'>{{$model->title}}</div>
    <div class="listtag">参与人数：{{$model->partnerNum}}</div>
    @if($model->days <= 0)
    <div class="listdate">活动已结束</div>
    <a href="#" class="tabBlock-tab join_button" style="border-color: #ccc;background-color: #aaaaaa;color:#fff;">立即参与</a>
    @elseif($model->from_date > date('Y-m-d H:i:s'))
    <div class="listdate">活动未开始</div>
    <a href="#" class="tabBlock-tab join_button" style="border-color: #ccc;background-color: #aaaaaa;color:#fff;">立即参与</a>
    @else
    <div class="listdate">剩余时间：{{$model->days}}天</div>
    <button class="join_button" type="button" onclick="$.ymFunc.goTo('{{$model->join_link}}')">立即参与</button>
    @endif
    <hr style="margin-bottom:0" />
    <pre style="margin:0 auto;color:#929292;font-size:1.3rem;border:none;background-color: #fff;text-align:justify;padding:15px">{{$model->text}}</pre>
</div>
@include('partview.tabswitchbar',[ 'list'=> [
    'join'=>['name'=>'最新参赛作品', 'url'=>'/activity/list/join/0/'.$model->id],
    'rank'=>['name'=>'作品排行榜',   'url'=>'/activity/list/rank/0/'.$model->id]
    ], 'active'=>$listName, 'inPage'=>true])
