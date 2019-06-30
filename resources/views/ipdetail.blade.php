@extends('layouts.block')
@section('content')
	@section('serverLoad')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$model->name])
<link rel="stylesheet" href="/css/ipdetail.css" />
<div class="ym_cm_card">
    <div class="ym_ip_contribution" onclick="$.ymFunc.goTo('/ip/list/user/0/{{$id}}')">
    {{ $model->contributorsCount }}人对此作品有贡献
    <i class="ymicon-right"></i>
    </div>
    <hr class="ym_cm_hr" />
    <div class="ym_ip_title">
    {{ $model->name }}
    <div class="ym_ip_debug" style="display:none">纠错&nbsp;&nbsp;<span style="color:#929292;">|</span>&nbsp;&nbsp;补充</div>
    </div>
    <div class="ym_ip_infobox">
        <div class="ym_ip_cover">
          <img src="{{ $model->cover->getPath(1,'483h_343w_1e_1c')}}" />
          <span>{{$model->ipTypeLabel}}</span>
        </div>
        <div class="ym_ip_info">
            <div class="ym_ip_info_starbox">
                @for($i=0;$i<5;$i++)
                    @if($model->averageScore>$i)
                <i class="ymicon-star ym_active"></i>
                    @else
                <i class="ymicon-star"></i>
                    @endif
                @endfor
            </div>
            @if(!empty($model->timeAttr) || !empty($model->statusAttr))
            <div class="ym_ip_info_tag0">
                @if(!empty($model->timeAttr))
                {{ $model->timeAttr }} &nbsp;&nbsp; 
                @endif
                {{$model->statusAttr}}
            </div>
            @endif
            <div class="ym_ip_info_tag">
            @if(!empty($model->numberAttr))
            {{$model->numberAttr}} &nbsp;&nbsp;
            @endif
            {{$model->authorAttr}}
            </div>
            <div class="ym_ip_info_tag">
            @for($i=0;$i<count($model->tags) && $i<6;$i++)
                {{ $model->tags[$i] }}&nbsp;&nbsp;
            @endfor
            </div>
            <div class="ym_ip_info_rec" style="display:none">
            <button type="button"><i class="ymicon-good"></i>&nbsp;&nbsp;推荐</button> <span>已经推荐260次</span>
            </div>
        </div>
    </div>
    <hr class="ym_cm_hr" style="margin-top:20px" />
   <div class="ym_cm_cardheader no_border">
        主要人物 <span onclick="$.ymFunc.goTo('/roles/list/default/0/{{$id}}')">更多 &nbsp;<i class="ymicon-right"></i></span>
   </div>
    <div class="ym_lzdiv ym_ip_rolebox" viewpath="/ip/{{ $id }}/roles"></div>
    <hr class="ym_cm_hr" />
    <div class ="ym_ip_controlbox">
    <label onclick="$.ymFunc.switchLike('ip',{{$model->id}}, afterLike)">我喜欢</label>
    <label onclick="$.ymFunc.switchLike('ip',{{$model->id}}, afterLike)" style="margin-left:5px;">
        <i id="likeHeart" class="{{$model->isLike?'ymicon-heart':'ymicon-heart-o'}}"></i>
    </label>
    <label style="margin-left:15px;">我的评价</label>
    <label style="margin-left:10px;" id="userScoreBox">
        @for($i=0;$i<5;$i++)
            @if($model->userScore>$i)
        <i class="ymicon-star ym_active" onclick="setScore({{$model->id}},{{$i+1}})"></i>
            @else
        <i class="ymicon-star" onclick="setScore({{$model->id}},{{$i+1}})"></i>
            @endif
        @endfor
    </label>
   </div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        内容简介 <span onclick="showAllIntro(this)">显示全部 &nbsp;</span>
    </div>
    <div class="ym_lzdiv" viewpath="/ip/{{ $id }}/intro"></div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        经典台词 <span onclick="$.ymFunc.goTo('/ipdialogue/list/verified/0/{{$model->id}}')">更多 &nbsp;<i class="ymicon-right"></i></span>
    </div>
    <div class="ym_lzdiv" viewpath="/ip/{{$id}}/dialogue" style="min-height:10rem"></div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        经典场景 <span onclick="$.ymFunc.goTo('/ipscene/list/verified/0/{{$model->id}}')">更多 &nbsp;<i class="ymicon-right"></i></span>
    </div>
    <div class="ym_lzdiv" viewpath="/ip/{{$id}}/scene" style="min-height:10rem"></div>
</div>

<div class="ym_cm_card">
    <div class="ym_cm_cardheader no_border">
        热门长评 <span onclick="$.ymFunc.goTo('/ip-disc/list/v/0/{{$model->id}}')">更多 &nbsp;<i class="ymicon-right"></i></span>
    </div>
    <div class="ym_lzdiv" viewpath="/ip/{{$id}}/discussion" style="min-height:10rem"></div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        <label id="lb_coll" class="ym_active" onclick="relateLabelChange('coll')">同人</label>
        <label id="lb_peri" onclick="relateLabelChange('peri')">周边</label>
        <label id="lb_dim" onclick="relateLabelChange('dim')">次元</label>
        <span onclick="$.ymFunc.goTo('/ip-coll/list/v/0/{{$model->id}}')">更多 &nbsp;<i class="ymicon-right"></i></span>
    </div>
    <div id="div_coll" class="ym_ip_related ym_active ym_lzdiv" viewpath="/ip/{{ $id }}/coll">
    </div>
    <div id="div_peri" class="ym_ip_related am-animation-fade" viewpath="/ip/{{ $id }}/peri">
    </div>
    <div id="div_dim" class="ym_ip_related am-animation-fade" viewpath="/ip/{{ $id }}/dim">
    </div>
</div>
<div class="ym_cm_card">
    <div class="ym_lzdiv" viewpath="/ip/{{ $id }}/expert" style="min-height:5rem"></div>
</div>
<div class="ym_cm_card">
    <div class="ym_lzdiv ym_comment_container" viewpath="/discussion/all-ip-{{$id}}"></div>
</div>
	@show
@include('partview.commentbar',
    ['resource'=>'ip',
    'resourceId'=>$model->id,
    'addFuncs'=>[ '发布场景'=>'/ipscene/create/'.$id,
                  '发布台词'=>'/ipdialogue/create/'.$id,
                  '发布角色'=>'/roles/create/'.$id,
                  '发布长评'=>'/pub/create-discussion/'.$id,
                  '发布同人'=>'/pub/create-coll/'.$id,
                  '发布周边'=>'/pub/create-peri/'.$id,
                  '创建次元'=>'/dimension/create/'.$id ]])
	@parent
	@section('runScript')
<script type="text/javascript">
 $(function() {
    $('#moretag').on('click',function(){
    $("#doc-dropdown-js").dropdown('toggle') ;
    });
  });
function afterLike(res,id, isLike){
    var $likeHeart = $('#likeHeart');
    if(isLike){
        $likeHeart.addClass('ymicon-heart');
        $likeHeart.removeClass('ymicon-heart-o');
        $likeHeart.css('color','#f7535a');
    }else{
        $likeHeart.addClass('ymicon-heart-o');
        $likeHeart.removeClass('ymicon-heart');
        $likeHeart.css('color','#383838');
    }
}
function setScore(id, score){
    $.post('/common/userscore',
    {'_token':$('meta[name="csrf-token"]').attr('content'),
     'resource':'ip',
     'resourceId':id,
     'score':score},
        function(data){
        if(!data.res){
            $.ymFunc.goLogin(0);
            return;
        }
        var $scoreBox = $('#userScoreBox');
        for(var i=1; i<=5; i++){
            var $icon = $scoreBox.find('i:nth-child('+i+')');
            if(score >= i) $icon.addClass('ym_active');
            else if(score < i) $icon.removeClass('ym_active');
        }
        }).error(function(e){alert(e.responseText);});
}
function showAllIntro(obj){
    $('.ym_ip_content.is_half').removeClass('is_half');
    $(obj).remove();
}
function relateLabelChange(obj){
    var $label = $('#lb_'+obj);
    if($label.hasClass('ym_active')) return;
    $label.parent().find('label.ym_active').removeClass('ym_active');
    $label.addClass('ym_active');
    $label.parent().find('span').on('click', function(){
       var url = "/ip-"+obj+"/list/v/0/{{$model->id}}";
       if(obj == 'dim'){
           url = "/dimension/list/ip/0/{{$model->id}}";
       }
       $.ymFunc.goTo(url);
    });
    $('#div_'+obj).parent().find('div.ym_ip_related.ym_active').removeClass('ym_active');
    $('#div_'+obj).addClass('ym_active');
    var lz = new lasyLoad('#div_'+obj);
    lz.load();
}
</script>
	@stop
@stop
