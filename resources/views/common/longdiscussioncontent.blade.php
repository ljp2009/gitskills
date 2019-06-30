
@foreach($results as $value)
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
        <span  name="_time" thetime="{{$value->created_at}}" class="post-time">{{$value->created_at}}</span>
      </div>
     </div>
    <div class="am-container user-post">
      <!--title-->
      @if($value->title)
      <div class="post-content" onclick="window.location='{{$value->linkUrl}}'"
      style="padding:0 0;font-weight:bold;font-size:1.5rem">{{$value->title}}</div>
      @endif
      <!--content Info-->
      @if(!is_null($value->text))
      <div class="post-content" onclick="window.location='{{$value->linkUrl}}'">
        <pre class="ym-content-break ym-content-margin">{{ $value->getShotTitle(50)}}</pre>
      </div>
      @endif

      <div class="post-content">
      <ul class="am-avg-sm-4">
        <li style="text-align:center"
          @if(Auth::check() && !$value->iLike)
            onclick = "$YM_COMMON.postLikeForList('long_discussion', {{$value->id}}, 'discussion_{{$value->id}}_like')";
          @endif
        >
        <i id='{{$value->objectType}}_{{$value->objectId}}_like'
          class="@if(!$value->iLike) am-icon-heart-o ym-icon-like @else am-icon-heart ym-icon-liked @endif"
            style="background-size:75%;background-position: center 56%;"></i><span class="ym-ft-10">喜欢</span>
        </li>
<!--        <li style="text-align:center">
         <i class="am-icon-warning ym-c-yellow"></i><span class="ym-ft-10">举报</span></li>
         <li style="text-align:center">
         <i class="ym-c-yellow am-icon-comment-o"></i><span class="ym-ft-10"> 回复</span></li> -->
        @if(Auth::check() && Auth::user()->id == $value->user->id)
        <li style="text-align:center">
        <i class="am-icon-trash-o ym-c-red" data-id="{{$value->id}}"><span class="ym-ft-10" style="font-style:normal;">删除</span></i></li>
        @endif
      </ul>
      </div>
	  <div id="discuss_{{$value->id}}" class="ym_lzdiv"
       viewpath="/common/longdiscussreply/newest-long_discussion/{{$value->id}}" >
        </div>
      <script type="text/javascript">(new lasyLoad("#discuss_{{$value->id}}")).load();</script>
    </div>
  </div>
<hr class="ym-border-hr ym-r-header" style="margin:1rem 0;border:1px solid #eeeeee;" />
@endforeach
