@if(count($models)>0)
   @foreach($models as $key=>$value)
        <div class="my-work-box ym_listitem" style="text-align:left">
            <div class ="ym-header" onclick="window.location='/user/product/{{$value['id']}}'">
                {{$value['name']}}</div>
            @if($value->image != [])
            <div class="ym-img">
                <a href="/user/product/{{$value['id']}}">
                    <img src="{{$value->image[0]}}" alt="" style="width:100%">
                </a>
            </div>
            <div class="ym-imgintro" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->intro}}</div>
            @else
                <div class="ym-intro" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->intro}}</div>
            @endif
            <div class="am-container" style="text-align:right;color:#666666;font-size:1rem">
            上传于 {{date('Y-m-d',strtotime($value->created_at))}}
            <span class="am-badge am-radius am-fl am-badge-warning">售价：{{$value->price}}</span>
           </div>
            <div class="am-line"></div>
            <div class="am-g" style="padding:7px 0; text-align:center">
            <ul class="am-avg-sm-{{$isOwner?4:2}}">
                <li>
                @if(Like::isLoginUserLike('user_production',$value->id))
                    <i class="am-icon-heart" style="color:red"></i>
                @else
                    <i class="am-icon-heart-o" style="color:red" onclick="doLikeProduction({{$value['id']}},this)"></i>
                @endif
                    <span>{{$value->likeCount}}</span>
                </li>
                <li>
                    <i class="am-icon-comment-o" onclick="window.location='/user/product/{{$value['id']}}'"></i>
                    <span>{{$value->comment}}</span>
                </li>
            @if($isOwner)
                <li>
                    <i class="am-icon-edit" onclick="window.location='/pub/modify/{{$value['id']}}'"></i>
                </li>
                <li>
                    <i class="am-icon-trash" onclick="deleteComfirm({{$value['id']}})"></i>
                </li>
            @endif
            </ul>
            </div>
        </div>
   @endforeach
@endif
