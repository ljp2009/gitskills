<?php
/* 评论栏
 * 页面必填参数：
 * placeholder: 输入框的placeholder，如果未指定则显示“发表您的评论吧”
 * resource: ip, userproduction, dimension, scene, dialogue, role, activity, task
 * resourceId: 目标Id
 * addBtnType:none: 不显示，form: 弹出输入页面, link: 跳转到指定地址, menu:显示菜单
 * menu: ['text'=>'url'] 菜单数组，显示文字与目标页面url的键值对
 * 页面方法
 * */
?>
<script src="/js/emoji.js"></script>
<div class="ym_commentbar" style="{{isset($addFuncs)?'':'padding-right:55px'}}">
    <input type="hidden" id="commentbar_resource" value="{{$resource}}"/>
    <input type="hidden" id="commentbar_resource_id" value="{{$resourceId}}"/>
    <div class="ym_commentbar_inputbox">
        <input type="text" style="display:none" placeholder="{{isset($placeholder)?$placeholder:''}}" maxlength="200"/>
        <div id="commentbar_text"  contentEditable="true" class="input_div" tabindex='1' placeholder="{{isset($placeholder)?$placeholder:'说点什么(150字)'}}" maxlength="150" ></div>
        <span class="ymicon-emoji emoji_icon"></span>
    </div>
    <div class="ym_commentbar_btnbox" >
        @if(Auth::check())
        <span><i class="ymicon-send" onclick="$.ymCommentBar.submit()"></i></span>
        @else
        <span><i class="ymicon-send" onclick="$.ymFunc.goTo('/')"></i></span>
        @endif
        <span id="ym_commentbar_count">0</span>
        @if(isset($addFuncs))
        <span onclick="$.ymAddPanel.show()" style="margin-left:5px"><i class="ymicon-add2" style="color:#ef7c1e;"></i></span>
        @endif
    </div>
</div>
<script>
$('.emoji_icon').bindEmoji('#commentbar_text');
$('#commentbar_text').on('keydown paste', function(event) {
    if($(this).text().length >= $(this).attr('maxlength') && event.keyCode != 8) { 
      event.preventDefault();
    }
});
</script>
@if(isset($addFuncs))
    @include('partview.addpanel', ['addFuncs'=> $addFuncs])
@endif
