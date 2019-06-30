<?php $isCurUserLike = Like::isLoginUserLike($resourceName, $resourceId); ?>
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default ym-ft-black" id="toolbar">
<ul class="ym-navbar-nav am-cf" style="background-color: #fff;border-top:solid 1px #eeeeee;" id="ym-footer">
	<li style="width:66%;">
		<input type="text" value="" class="shortdiscuss" placeholder="我来说两句" onfocus="showDiscussion()">
	</li>
<!-- 	<li>&nbsp;</li> -->
<!-- 	<li>&nbsp;</li> -->
<!-- 	<li>&nbsp;</li> -->
	<li>
	  @if($isCurUserLike<0)
	  <label><i class="am-icon-heart-o ym-ft-20 ym-icon-like" style="background-size:75%;background-position: center 56%;"></i>{{Like::getLikeCount($resourceName, $resourceId)}}</label>
	  @elseif($isCurUserLike == 0)
	  <label><i class="am-icon-heart-o ym-ft-20 ym-icon-like" style="background-size:75%;background-position: center 56%;" onclick="postLikeAndReturnCount($(this), '{{$resourceName}}', '{{$resourceId}}')"></i>{{Like::getLikeCount($resourceName, $resourceId)}}</label>
	  @else
	  <label><i class="am-icon-heart ym-ft-20 ym-icon-liked" style="background-size:75%;background-position: center 56%;"></i>{{Like::getLikeCount($resourceName, $resourceId)}}</label>
	  @endif
	</li>
	<li>
	  <label><i class="am-icon-comment-o ym-ft-20 ym-icon-comment" style="background-size:75%;background-position: center 56%;"></i>{{Discussion::getDiscussionCount($resourceName, $resourceId)}}</label>
	</li>
</ul>
</div>

<div class="short-discussion">
<p>我来说两句</p>
	<textarea placeholder="我来说两句" name="shortdiscuss">
	</textarea>
	<button id="sendComment" class="am-btn am-btn-primary" onclick="sendComment()">提交</button>
</div>
<div class="comment-wrap" onclick="hideDiscussion()">
</div>
<div class="am-modal am-modal-alert" tabindex="-1" id="comment-msg" style="width:70%;left:15%;margin-left:0;">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示</div>
    <div class="am-modal-bd">

    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn">确定</span>
    </div>
  </div>
</div>
<script type="text/javascript">
function sendComment(){
	var text = $.trim($('.short-discussion').find('textarea[name="shortdiscuss"]').val());
	var $modal = $('#comment-msg');
    var $target = $modal;
	@if(!Auth::check())
		location.href='/';
		return false;
	@endif
	if(!text){
		tipMsg('请输入评论后再提交');
		return false;
	}
	hideDiscussion();
	$.ajax({
		type:'POST',
		url:'/common/discuss/create/short',
		data:{content:text,resource:"{{$resourceName}}",resourceId:'{{$resourceId}}',_token:"{{ csrf_token() }}"},
		dataType:'json',
		success:function(data){
			if(data.code == 1){
				location.reload();
			}
		}
	});
}
function tipMsg($msg){
	var $modal = $('#comment-msg');
      var $target = $modal;
      if (($target).hasClass('js-modal-open')) {
        $modal.modal();
      } else if (($target).hasClass('js-modal-close')) {
        $modal.modal('close');
      } else {
        $modal.modal('toggle');
      }
  	  $('#comment-msg .am-modal-bd').text($msg);
}
function showDiscussion(){
	$('.short-discussion').animate({'bottom':'0px'},200);
	$('.comment-wrap').show();
	$('body').css('overflow','hidden');
	$('.short-discussion textarea').focus();
}

function hideDiscussion(){
	$('.short-discussion').animate({'bottom':'-180px'},200);
	$('.comment-wrap').hide();
	$('body').css('overflow','');
}
function postLikeAndReturnCount(o, resourceName, resourceId){
    var className = "ym-icon-liked";
    var ele = o;
        if(ele.hasClass(className)){
            return;
        }
        $.post('/common/likeAndCount', {'_token':$('meta[name="csrf-token"]').attr('content'),
            'resource':resourceName, 'resourceId':resourceId},
            function(data){
                ele.parent().html('<i class="am-icon-heart ym-ft-20 ym-icon-liked" style="background-size:75%;background-position: center 56%;"></i>' + data);
            });
}
</script>
