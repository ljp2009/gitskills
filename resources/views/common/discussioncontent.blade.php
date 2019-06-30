@foreach($results as $res)
  <div class="am-g ym-r-header" name="oneitem">
	<div class="am-u-sm-2">
	 <a href="{{$res->user->homeUrl}}">
	  <img class="am-circle" style="display: inline-block; height: 4rem; margin-top: 0.3rem "
		   src="{{$res->user->avatar}}" />
	 </a>
	</div>
	<div class="am-u-sm-9" >
	  <label class="ym-ft-17 ym-c-black" style="padding: 0;margin: 0" >{{ $res->user->display_name }}</label>
		<br />
		<label class="ym-ft-09 ym-c-grey"  style="padding: 0;margin: 0"><span name="_time" thetime="{{ $res->created_at }}"></span></label>
	</div>
	<div class="am-u-sm-1">
	 	@include('common.like', array('likeObj'=>array('resourceName'=>'discussion', 'resourceId'=>$res->id, 'isLike'=>$res->iLike )))
		<a href="/common/discuss/reply/{{$res->id}}/{{$res->id}}"><i class="am-icon-comment-o ym-ft-15 am-fr"></i></a>
		@if(Auth::check()&&Auth::user()->id==$res->user->id)
		<i class="am-icon-trash-o ym-c-red ym-ft-15 am-fr" style="font-size:1.8rem" data-id="{{$res->id}}"></i>
		@endif
	</div>
	<div class="am-u-sm-11" style="padding-top: 0.5rem">
	  <?php $content = Utils::cutString($res->text, Discussion::$TEXT_COLLAPSE_LEN); $canExpand = $content['canExpand']; $shortContent = $content['content']; ?>
	  <label class="ym-c-grey ym-ft-15" id="_discussion_{{$res->id}}_short">{{ $shortContent }}</label>
	  @if($canExpand)
	  <label class="ym-c-grey ym-ft-15" style="display:none" id="_discussion_{{$res->id}}_full">{{ $res->text }}</label>
	  <div id="_discussion_{{$res->id}}_action" class="ym_text_expand_action"><a href="javascript:$YM_COMMON.displayDiscussionFullContent({{$res->id}})">全部内容</a></div>
	  @endif
	  @if($res->replyCount > 0)
		<?php 
           $left = $res->replyCount - Discussion::$REPLY_LIMIT_UNLOGIN;
           $ct = 0;
           foreach ($res->replies as $onereply) {
               if (Auth::check() && Auth::user()->id == $onereply->user_id) {
                   $deleteHtml = '<i class="am-icon-trash-o ym-c-red ym-ft-15 am-fr" style="font-size:1.8rem" data-id="'.$onereply->id.'"></i>';
               } else {
                   $deleteHtml = '';
               }
               echo '<div class="ym_discussion_reply am-u-sm-11">'.$onereply->user->display_name.'回复: '.$onereply->text.'
		   		<span name="_time"  class="ym_discussion_reply_time"  thetime="'.$onereply->created_at.'"></span>
	  			
	  			</div>'.$deleteHtml.'<div class="am-cf"></div>';
               ++$ct;
               if ($ct >= Discussion::$REPLY_LIMIT_UNLOGIN) {
                   break;
               }
           }
        ?>
		@if($left > 0)
		<?php
            $ct = 0;
            echo '<div id="_discussion_reply_'.$res->id.'" style="display:none">
			';
            foreach ($res->replies as $onereply) {
                ++$ct;
                if (Auth::check() && Auth::user()->id == $onereply->user_id) {
                    $deleteHtml = '<i class="am-icon-trash-o ym-c-red ym-ft-15 am-fr" style="font-size:1.8rem" data-id="'.$onereply->id.'"></i>';
                } else {
                    $deleteHtml = '';
                }
                if ($ct > Discussion::$REPLY_LIMIT_UNLOGIN) {
                    echo '<div class="ym_discussion_reply am-u-sm-11">'.$onereply->user->display_name.'回复: '.$onereply->text.'
						<span name="_time" class="ym_discussion_reply_time" thetime="'.$onereply->created_at.'"></span></div>'
                        .$deleteHtml.'<div class="am-cf"></div>';
                }
                if ($ct >= Discussion::$REPLY_MOST_ITEMS) {
                    echo '<p class="ym_discussion_reply_mark"><a href="/common/discuss/reply/list/'.$res->id.'/0">查看更多</a></p>';
                    break;
                }
            }
            echo '</div>';
        ?>
		<p class="ym_discussion_reply_mark" id="_discussion_reply_{{$res->id}}_mark">
			<a href="javascript:$YM_COMMON.displayDiscussionAllReplies({{$res->id}})">还有{{$left}}条回复</a>
		</p>
		@endif
	  @endif
	<hr style="margin:0.5rem 0 0 0" />
   </div>
   @if ($res->type == 1&&Auth::check()&&Auth::user()->id==$res->user_id)
   <div class="am-u-sm-1" onclick="window.location='/common/discuss/edit/{{$res->id}}'">
	 <i class="am-icon-edit ym-c-gray ym-ft-15 am-fr" style="font-size:1.7rem"></i>
	</div>
	@endif
</div>
 @endforeach
 <script type="text/javascript">
 $hasDiscussion = true;
 </script>
