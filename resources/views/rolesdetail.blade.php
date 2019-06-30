@extends('layouts.block')
@section('title','角色')
@section('content')
@parent
@section('serverload')
<link rel="stylesheet" href="/css/production.css">
<link rel="stylesheet" href="/css/list.css">
<link rel="stylesheet" href="/css/ym_skill.css">

@if($isOwner)
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 
          'right'=>[
            '首页'=>'$.ymFunc.goTo("/reshall")',
		    '-'=>'',
            '编辑角色'=>'$.ymFunc.goTo("/roles/edit/'.$model->id.'")',
            '删除角色'=>'deleteRole()',
		    '添加技能'=>'addSkill();'], 'pageTitle'=>'角色'])
@else
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'角色'])
@endif
<?php
$parentItem=[];
$parentItem['url'] = '/ip/'.$model->ip->id;
$parentItem['imgPath'] = $model->ip->cover;
$parentItem['title'] = $model->ip->name;
$parentItem['text'] = $model->ip->cardInfo;
 ?>
<div class="ym_cm_card have_border">
    <div class="ym_up_info">
        <img class="ym_up_info_avatar" style="border-radius:3px;" src= "{{$model->header->getpath(1,'80h_80w_1e_1c')}}"
                onclick="" />
        <label class="ym_up_info_title">{{$model->name}}</label>
        <label class="ym_up_info_user">{{$model->user->display_name}} 发布于 {{date('Y-m-d',strtotime($model->created_at))}}</label>

        <div class="ym_up_info_text" style="margin-left:-50px;">
           <div class="info_text">
            {!!$model['intro']!!}
            @foreach($model->image as $img)
            <img src="{{$img->getPath(1)}}" alt="" style="width:100%">
            @endforeach
            </div>
        </div>
    </div>
		@if($model->image->checkSet())
		<div class="role-img-big">
			<img src="{{$model->image->getPath()}}" alt="" class="am-img-responsive">
		</div>
        @endif
		<!-- 技能 -->
        <div class="ym_lzdiv" viewpath="/roles/{{ $id }}/skill"></div>
		@if(!empty($parentItem['url']))
        <div class="ym_up_ipcard"  onclick="window.location = '{{$parentItem['url']}}'">
        @else
        <div class="ym_up_ipcard">
        @endif
        @if(!empty($parentItem['imgPath']))
            <img src="{{$parentItem['imgPath']->getpath(1,'58h_58w_1e_1c')}}" class="ym_up_ipcard_img" />
        @endif
            <div class="ym_up_ipcard_info">
               <span class="ym_up_ipcard_info_title">{{$parentItem['title']}}</span>
                @if(!empty($parentItem['text']))
               <span class="ym_up_ipcard_info_text">{{$parentItem['text']}}</span>
                @endif
            </div>
        </div>
        <div class="ym_cm_listitem_controlbox">
            <label onclick="$.ymListItem.likeListItem('{{$listItem->resource}}',{{$listItem->id}},this)">
                <i class="{{$listItem->checkUserLike()?'ymicon-heart':'ymicon-heart-o'}}"></i><span>{{$listItem->getLikeCount()}}</span>
            </label>
            <label onclick="_YMShowShare.show('{{$listItem->url}}')">
                <i class="ymicon-share"></i><span>分享</span>
            </label>
            <label onclick="$.ymListItem.reportListItem('{{$listItem->resource}}',{{$listItem->id}},this)">
                <i class="ymicon-alter"></i><span>举报</span>
            </label>
            <label>
                <i class="ymicon-comment"></i><span>{{$listItem->getDiscCount()}}</span>
            </label>
        </div>
</div>
@if($isOwner)
 <form id="delForm" method="POST" action="/pub/delete">
    <input Type='hidden' name='id' value='{{$model->id}}' />
    <input Type='hidden' name='_token' value='{{csrf_token()}}' />
</form>

<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除确认</div>
    <div class="am-modal-bd">
      你，确定要删除这条记录吗，此操作会同步删除掉同人/周边/我的作品中的相关信息？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
@endif
<div class="ym_cm_card">
    <div class="ym_lzdiv ym_comment_container" viewpath="/discussion/all-ip_role-{{$id}}"></div>
</div>
@include('partview.commentbar', ['resource'=>'ip_role', 'resourceId'=>$id])

<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示</div>
    <div class="am-modal-bd">
      你，确定要删除这条记录吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>	
<div class="am-modal am-modal-no-btn ym_addpanel" tabindex="-1" id="doc-modal-skill">
  <div class="am-modal-dialog ym_addpanel">
    <div class="am-modal-bd" >
        <ul class="am-avg-sm-{{$cols or 2}}">
            <li><a href="javascript: void(0)" onclick="deleteSkill()">删除技能</a></li>
			<li><a href="javascript: void(0)" onclick="setMainSkill()">设置必杀技能</a></li>
        </ul>
        <input type="hidden" id="active_skill" value="" />
    </div>
  </div>
</div>
@stop
@section('runScript')
<script src="/js/listload.js"></script>
<script type="text/javascript">
var longTouch = new longTouch;
longTouch.bind({
	"parentContainer":".ym_lzdiv",
	"container":".am-icon-trash-o",
	"token":"{{ csrf_token() }}",
	"deleteInfo":"您确定删除该评论吗?",
	@if(isset($deleteRoute))
	"deleteRoute":'{{$deleteRoute}}',
	@endif
});
longTouch.delete_obj();
@if($isOwner)
function deleteRole(){
	$("#my-confirm").find('.am-modal-bd').html('你确定要删除这个角色吗？');
	$("#my-confirm").modal({
		onConfirm:function(d){
			$.post("/roles/delete",{
				"_token":"{{ csrf_token() }}",
				"id":"{{$model->id}}",
			},function(data){
				if(data == 'true'){
					window.location = '/ip/{{$model->ip->id}}';
				}else{
					alert("无法删除");
				}
			}).error(function(e){
				alert(e);
			});
		}
	});
}
function deleteSkill(id){
	$("#doc-modal-skill").modal('close');
    if(typeof(id) == 'undefined'){
	    id = $("#active_skill").val();
    }
	$("#my-confirm").find('.am-modal-bd').html('你确定要删除这个技能吗？');
	$("#my-confirm").modal({
		relatedTarget:id,
		onConfirm:function(d){
			$.post("/roles/deleteskill",{
				"_token":"{{ csrf_token() }}",
				"id":this.relatedTarget,
			},function(data){
				if(data != "false"){
                    location.reload();
				}else{
					alert("无法删除。");
				}
			}).error(function(e){
				alert(e);
			});
		}
	});
}
function setMainSkill(id){
    if(typeof(id) == 'undefined'){
	    id = $("#active_skill").val();
    }
	$.post("/roles/mainskill",{
		"_token":"{{ csrf_token() }}",
		"id":id,
	},function(data){
		if(data != "false"){
			location.reload();
		}else{
			alert("设置失败。");
		}
	}).error(function(e){
		alert(e);
	});

}
function openSkillModel(id){
	$("#active_skill").val(id);
	$("#doc-modal-skill").modal('open');
}
function addSkill(){
	if($('li.ym-skill-sel').length >= 4){
		alert('一个角色最多只能包含4个技能。');
		return;
	}
	window.location='/roleskill/create/{{ $model->id }}';
}
@endif
</script>
@stop
@stop

