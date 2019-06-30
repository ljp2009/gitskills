<div class="post-list ym_listitem">
	<div class="am-container">
    <div class="user-photo">
      <a href="{{ $value->user->homeUrl }}">
      <img src="{{$value->user->avatar}}" alt="" class="am-circle am-img-responsive"></a>
    </div>
    <!--User Info-->
    <div class="user-info">
        <a class="user-name">{{$value->user->display_name}}</a>
        @if($value->user->label != '')
        <span class="user-level">{{$value->user->label}}</span>
        @endif
        <span  name="_time" thetime="{{$value->createAt}}" class="post-time">{{$value->createAt}}</span>
      </div>
     </div>
    <div class="am-container user-post">
      <!--title-->
      @if($value->title)
      <div class="post-content" style="padding:0 0;font-weight:bold;font-size:1.5rem">{{$value->title}}</div>
      @endif
	  @if(isset($value->linkHtml) && !is_null($value->linkHtml) && $value->linkHtml == '')
      <div class="post-content"
      style="padding:0 0;font-size:1.3rem">{!!$value->linkHtml!!}</div>
      @endif
      <!--content Info-->
      @if(!is_null($value->text))
      <div class="post-content" >
        <pre class="ym-content-break ym-content-margin">{{$value->text}}</pre>
      </div>
      @endif

      <!--images-->
      @if(count($value->imageList)>0)
      <div class="post-content">
          <ul class="am-avg-sm-3 am-thumbnails">
          @foreach($value->imageList as $key=>$img)
          @if($img)
            <li style="padding:0px 3px 0px 3px" onclick="bigImg(this)">
              <img src="{{ $img }}" class="am-thumbnail" style="margin-bottom:5px;" >
                         <div class="am-modal am-modal-alert" tabindex="-1">
                <div class="am-modal-dialog">
                  <div class="am-modal-bd">
                    <img src="" style="width:100%" />
                  </div>
                </div>
              </div>
            </li>
          @endif
          @endforeach
          </ul>
      </div>
      @endif
      <div class="post-content">
      <?php $isOwner = (Auth::check() && Auth::user()->id == $value->user->id); ?>
      <ul class="am-avg-sm-{{$isOwner?4:2}}">
        <li style="text-align:center">
        <i class="am-icon-share-alt ym-c-yellow"></i><span class="ym-ft-10">分享</span></li>
        <li style="text-align:center">
        <i class="am-icon-warning ym-c-yellow"></i><span class="ym-ft-10">举报</span></li>
        @if($isOwner)
        <li style="text-align:center" onclick="location.href='{{$value->editUrl.$value->objectId}}'">
            <i class="am-icon-edit ym-c-yellow"  style='font-weight:100'></i>
            <span class="ym-ft-10" style="font-style:normal;">编辑</span>
        </li>
        <li style="text-align:center" onclick="deleteComfirm()">
            <i class="am-icon-trash ym-c-red"  style='font-weight:100'></i>
            <span class="ym-ft-10" style="font-style:normal;">删除</span>
        </li>
        @endif
      </ul>
      </div>
      @if(count($value->likeUsers)>0)
      <div class="post-content" style="clear:both;padding:1rem 0">
        <div class="ym-sub-lately">
        @for ($i = 0; $i < 6; $i++)
          @if(count($value->likeUsers)>$i)
            <a href="{{$value->likeUsers[$i]->homeUrl}}"><img src="{{$value->likeUsers[$i]->avatar}}" alt="" class="am-circle am-img-responsive"></a>
          @endif
        @endfor
         @if(count($value->likeUsers)>6)
          <a href="/user/list/likeuserlist-{{$value->objectType}}/0/{{$value->objectId}}" class="visit-num" style="font-size:0.9rem">{{$value->likeCount-6}}</a>
         @endif
        </div>
      </div>
      @endif
    </div>
    <div id="discuss_{{$value->objectId}}" class="ym_lzdiv"
     viewpath="/common/discuss/normal/{{$value->objectType}}/{{$value->objectId}}"
     fn="$YM_COMMON.applyTimeToObjects();$YM_COMMON.attachTabSwitchEvent('_discuss_head');"
     style="min-height:5rem"></div>
    @include('partview.detailfooter', array('resourceName'=>$value->objectType, 'resourceId'=>$value->objectId))
  </div>

@if($isOwner)
 <form id="delForm" method="POST" action="/{{$value->objectType}}/delete">
    <input Type='hidden' name='id' value='{{$value->objectId}}' />
    <input Type='hidden' name='_token' value='{{csrf_token()}}' />
</form>

<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除确认</div>
    <div class="am-modal-bd">
      你，确定要删除这条记录吗，此操作会同步删除掉我的作品中的相关信息？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<script type="text/javascript">
function deleteComfirm(){
   $('#my-confirm').modal({onConfirm:function(e){
        $("#delForm").submit();
    }});
}
</script>
@endif
