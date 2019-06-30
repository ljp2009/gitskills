
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
        <span  name="_time" thetime="{{$value->created_at}}" class="post-time">{{$value->created_at}}</span>
      </div>
     </div>
    <div class="am-container user-post">
      <!--title-->
      @if($value->title)
      <div class="post-content"
      style="padding:0 0;font-weight:bold;font-size:1.5rem">{{$value->title}}</div>
      @endif
      <!--content Info-->
      @if(!is_null($value->text))
      <div class="post-content">
        <pre class="ym-content-break ym-content-margin">{{ $value->text}}</pre>
      </div>
      @endif

      <div class="post-content">
      <ul class="am-avg-sm-4">
        <li style="text-align:center"
          @if($value->likeStatus == 1)
            onclick = "$YM_COMMON.postLikeForList('{{discussion}}', {{$value->id}}, 'discussion_{{$value->id}}_like')";
          @endif
        >
        <i id='discussion_{{$value->id}}_like' 
          class="am-icon-heart {{ $value->likeStatus == 2?'':'-o'}} ym-c-red"></i><span class="ym-ft-10">喜欢</span>
        </li>
      </ul>
      </div>
	  <div id="longdiscuss-reply"></div>
    </div>
  </div>
  @include('partview.detailfooter', array('resourceName'=>'long_discussion', 'resourceId'=>$value->id))