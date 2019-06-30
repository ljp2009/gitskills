@if(count($models)>0)
   @foreach($models as $key=>$model)
    <?php $value = $model['obj']; ?>
        <div class="my-work-box ym_listitem" style="text-align:left">
            <div class ="ym-header" onclick="window.location='/user/product/{{$value['id']}}'">
                {{$value['name']}}</div>
            @if($value->image != [])
            <div class="ym-img">
                <a href="/user/product/{{$value['id']}}">
                    <img src="{{$value->image[0]}}" alt="" style="width:100%">
                </a>
            </div>
            <div class="ym-imgintro" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->getShotIntro(140)}}</div>
            @else
                <div class="ym-intro" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->getShotIntro(140)}}</div>
            @endif
            <div class="am-container" style="text-align:right;color:#666666;font-size:1rem">
            @if(!is_null($value->relatedTypeLabel))
            <span class="am-badge am-radius am-fl">{{$value->relatedTypeLabel}}</span>
            @endif
            上传于 {{date('Y-m-d',strtotime($value->created_at))}}
            </div>
            <div class="am-line"></div>
            <div class="am-g" style="padding:7px 0; text-align:center">
            <ul class="am-avg-sm-{{$value->ownerIsMe?4:2}}">
                <li>
                @if(Like::isLoginUserLike('user_production',$value->id))
                    <i class="am-icon-heart" style="color:red"></i>
                @else
                    <i class="am-icon-heart-o" style="color:red" onclick="doLikeProduction({{$value['id']}},this)"></i>
                @endif
                    <span>{{$model['sum']}}</span>
                </li>
                <li>
                    <i class="am-icon-comment-o" onclick="window.location='/user/product/{{$value['id']}}'"></i>
                    <span>{{$value->comment}}</span>
                </li>
            @if($value->ownerIsMe)
                <li>
                    <i class="am-icon-edit" onclick="window.location='/pub/modify/{{$value['id']}}'"></i>
                </li>
                <li>
                    <i class="am-icon-trash" onclick="deleteComfirm({{$value['id']}})"></i>
                </li>
            @endif
            </ul>
            </div>
            @if($value->is_sell)
            <div class="ym-flag">
                <i class="am-icon-cny"></i>
            </div>
            @endif
        </div>
   @endforeach
@endif
