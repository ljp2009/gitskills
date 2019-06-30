@if(count($models)>0)
<input type="hidden" id="production_id">
   @foreach($models as $key=>$value)
        <div class="ym_cm_card ym_proditem ym_listitem my-work-box" style="text-align:left;background-color:#f5f5f9; border:0px;">
            <div class ="ym_proditem_header" onclick="window.location='/user/product/{{$value['id']}}'">
                <label>●&nbsp;上传于 {{date('Y-m-d',strtotime($value->created_at))}} </label>
            </div>
            <div class="ym_proditem_body">
                <div style="line-height:2.2rem;">
                    <label style="font-weight:700;width:90%;">{{$value['name']}}</label>
                    <i class="ymicon-right" style="color:#c2c2c2;float:right;"></i>
                </div>
                
                @if($value->cover->checkSet())
                <div class="ym-imgintro" style="padding:0px;max-height:6rem;overflow:hidden;" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->intro}}</div>
                <div class="ym-img" style="margin-top: 0.5rem;">
                    <a href="/user/product/{{$value['id']}}">
                        <img src="{{$value->cover->getPath(1,'290w_120h_1e_1c')}}" alt="" style="width:100%">
                    </a>
                </div>
                @else
                    <div class="ym-intro" style="padding:0px;overflow: hidden;height:10rem;" onclick="window.location='/user/product/{{$value['id']}}'">{{$value->intro}}</div>
                @endif
                <div class="am-g" style="padding-top:7px; text-align:center;margin:0rem 0.2rem;">
                <ul>
                    <li style="float:left;text-align:left;width:20%;" >
                        <label onclick="$.ymListItem.likeListItem('user_production',{{$value['id']}}, this)">
                            <i class="{{Like::isLoginUserLike('user_production',$value->id)?'ymicon-heart':'ymicon-heart-o'}}"></i>
                            <span>{{$value->likeCount}}</span>
                        </label>
                    </li>
                    <li style="float:left;text-align:left;width:20%;">
                        <i class="ymicon-comment" onclick="window.location='/user/product/{{$value['id']}}'"></i>
                        <span>{{$value->comment}}</span>
                    </li>
                @if($value->checkOwner())
                    <li style="float:right;text-align:right;width:40%;">
                        <a style="border-right: #e2e2e2 0.1rem solid;padding:0rem 1rem 0rem 1rem;" onclick="deleteProduction({{$value['id']}})">删除</a>
                        <a style="margin-left: -1rem;" href="/pub/modify/{{$value['id']}}">修改</a>
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
            
        </div>
   @endforeach
   @if($value->checkOwner())
   @endif
@endif
