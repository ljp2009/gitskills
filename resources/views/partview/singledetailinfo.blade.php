<div class="post-list ym_listitem">
	<div class="am-container">
    <div class="user-photo">
      <a href="{{ $value->user->homeUrl }}">
      <img src="{{$value->user->avatar}}" alt="" class="am-circle am-img-responsive"></a>
    </div>
    <!--User Info-->
    <div class="user-info" onclick="window.location='{{$value->linkUrl}}'">
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
      <div class="post-content" onclick="window.location='{{$value->linkUrl}}'"
      style="padding:0 0;font-weight:bold;font-size:1.5rem">{{$value->title}}</div>
      @endif
	  @if(isset($value->linkHtml) && !is_null($value->linkHtml) && $value->linkHtml == '')
      <div class="post-content" 
      style="padding:0 0;font-size:1.3rem">链接：{!!$value->linkHtml!!}</div>
      @endif
      <!--content Info-->
      @if(!is_null($value->text))
      <div class="post-content" onclick="window.location='{{$value->linkUrl}}'">
        <pre class="ym-content-break ym-content-margin">{{ $value->text}}</pre>
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
      <ul class="am-avg-sm-4">
        <li style="text-align:center"
          @if($value->likeStatus == 1)
            onclick = "$YM_COMMON.postLikeForList('{{$value->objectType}}', {{$value->objectId}}, '{{$value->objectType}}_{{$value->objectId}}_like')";
          @endif
        >
        @if(!$isFullDis)
        <i id='{{$value->objectType}}_{{$value->objectId}}_like' 
          class="@if($value->likeStatus == 1) am-icon-heart-o ym-icon-like @elseif($value->likeStatus == 2) am-icon-heart ym-icon-liked @endif" style="background-size:75%;background-position: center 56%;"></i><span class="ym-ft-10">喜欢</span>
        </li>
        
       <!-- <li style="text-align:center">
         <i class="am-icon-comment-o ym-c-yellow"></i><span class="ym-ft-10">评论</span></li> -->
        @endif
        <li style="text-align:center">
        <i class="am-icon-share-alt ym-c-yellow"></i><span class="ym-ft-10">分享</span></li>
        <li style="text-align:center">
        <i class="am-icon-warning ym-c-yellow"></i><span class="ym-ft-10">举报</span></li>
        @if(!$isFullDis && $value->isDelete && Auth::check() && Auth::user()->id == $value->user->id)
        <li style="text-align:center">
        <i class="am-icon-trash-o ym-c-red" data-id="{{$value->objectId}}"><span class="ym-ft-10" style="font-style:normal;">删除</span></i></li>
        @elseif(!$isFullDis && Auth::check() && Auth::user()->id == $value->user->id && isset($value->editUrl))
        <li style="text-align:center" onclick="location.href='{{$value->editUrl.$value->objectId}}'">
        <i class="am-icon-edit ym-c-yellow"  style='font-weight:100'></i><span class="ym-ft-10" style="font-style:normal;">编辑</span></li>
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
      @if(!$isFullDis)
        <div id="discuss_{{$value->objectId}}" class="ym_lzdiv" 
        viewPath="/common/discuss/newest-{{$value->objectType}}/{{$value->objectId}}" ></div>
        <script type="text/javascript">(new lasyLoad("#discuss_{{$value->objectId}}")).load();</script>
      @endif
	  
    </div>
  </div>
<hr class="ym-border-hr ym-r-header" style="margin:1rem 0;" />  
