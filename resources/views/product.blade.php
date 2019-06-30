@extends('layouts.block')
@section('title','作品')
@section('content')
@parent
@section('serverload')
<link rel="stylesheet" href="/css/production.css">
<link rel="stylesheet" href="/css/list.css">
<link href="/css/ym_dialog.css" rel="stylesheet" />
<link href="/css/scContentEditor.css" rel="stylesheet" />
<script src="/assets/common/commonDialog.js"></script>
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>$model->label])
<div class="ym_cm_card have_border">
    <div class="ym_up_info">
        <img class="ym_up_info_avatar" src={{$model->user->avatar->getPath(2,'80w_80h_1e_1c')}}
                onclick="$.ymFunc.goTo('{{$model->user->homeUrl}}')" />
        <label class="ym_up_info_title">
            {{$model->title}}
        </label>
        <label class="ym_up_info_user">{{$model->user->display_name}} 发布于 {{date('Y-m-d',strtotime($model->created_at))}}</label>
        @if($model->checkOwner())
        <div class="ym_up_info_owner">
            <input type="hidden" id="production_id">
            <a href="javascript:void(0)" onclick="deleteProduction()">删除</a>
            <span>|</span>
            <a href="javascript:void(0)" onclick="$.ymFunc.goTo('/pub/modify/{{$model->id}}')">编辑</a>
        </div>
        @endif
        <div class="ym_up_info_text" style="margin-left:-50px;">
        @if(count($model->contents) == 0)
           <div class="info_text">
            {!!$model->formatText!!}
            {!!$model->getLinkA()!!}
            @foreach($model->images as $img)
            <img src="{{$img->getPath(1)}}" alt="" style="width:100%">
            @endforeach
            </div>
        @else
            <div class="sc_ce">
            @foreach($model->contents as $content)        
                @if($content->type == 'text')
                <div class="sc_ce_par_text {{$content->isBold()?'is_bold':''}} {{$content->isReference()?'is_ref':''}}"><pre>{{$content->text}}</pre></div>
                @elseif($content->type == 'image')
                <div class="sc_ce_par_image {{$content->isFit()?'is_fit':''}}"><img src="{{$content->url}}" /><label>{{$content->text}}</label></div>
                @elseif($content->type == 'link')
                <div class="sc_ce_par_link"><span onclick="$.ymFunc.goTo('{{$content->url}}')">{{$content->text}}</span></div>
                @endif
            @endforeach
            </div>
        @endif
        </div>

    </div>
    @if(!is_null($model->ip))
    <div class="ym_up_ipcard" onclick="window.location = '/ip/{{$model->ip->id}}'">
        <img src="{{$model->ip->cover->getPath(1,'58h_58w_1e_1c')}}" class="ym_up_ipcard_img" />
        <div class="ym_up_ipcard_info">
           <span class="ym_up_ipcard_info_title">{{$model->ip->name}}</span>
           <span class="ym_up_ipcard_info_text">{{$model->ip->cardInfo}}</span>
        </div>
    </div>
    @endif
    <div class="ym_cm_listitem_controlbox">
        <label onclick="$.ymFunc.switchLike('user_production',{{$model->id}},updateLike)" id="likeControl">
            <i class="{{$model->checkUserLike()?'ymicon-heart':'ymicon-heart-o'}}"></i><span>{{$model->like_sum}}</span>
        </label>
        <label onclick="_YMShowShare.show('{{$model->detailUrl}}')">
            <i class="ymicon-share"></i><span>分享</span>
        </label>
        <label onclick="$.ymListItem.reportListItem('{{$model->id}}',{{$model->id}},this)">
            <i class="ymicon-alter"></i><span>举报</span>
        </label>
        <label>
            <i class="ymicon-comment"></i><span>{{$model->getDiscCount()}}</span>
        </label>
    </div>
</div>
<div class="ym_cm_card">
    <div class="ym_lzdiv ym_comment_container" viewpath="/discussion/all-user_production-{{$model->id}}"></div>
</div>
@if($model->checkOwner())
 <form id="delForm" method="POST" action="/pub/delete">
    <input Type='hidden' name='id' value='{{$model->id}}' />
    <input Type='hidden' name='_token' value='{{csrf_token()}}' />
</form>

@endif
@include('partview.commentbar', ['resource'=>'user_production', 'resourceId'=>$model->id])
@include('partview.share')
@stop
@section('runScript')
<script type="text/javascript">
@if($model->checkOwner())
    //删除
    function deleteProduction(){
        $('#production_id').click();
    }

    //绑定删除按钮
    $('#production_id').commonDialog({
        type:'confirmAndCancelDialog',
        content:'您，确定要删除这条记录吗，此操作会同步删除掉同人/周边/我的作品中的相关信息？'
    })
    .bind('confirmAndCancelDialog', function(res){
        $("#delForm").submit();
    }, null);
@endif
    function updateLike(resource, resourceId, likeStatus){
        var $icon = $('#likeControl').find('i');
        var $ct = $('#likeControl').find('span');
        var ct =parseInt($ct.text());
        if(likeStatus){
            $icon.removeClass('ymicon-heart-o');
            $icon.addClass('ymicon-heart');
            $ct.text(ct+1);
        }
        else{
            $icon.removeClass('ymicon-heart');
            $icon.addClass('ymicon-heart-o');
            $ct.text(ct-1);
        }
    }
</script>
@show
@stop

